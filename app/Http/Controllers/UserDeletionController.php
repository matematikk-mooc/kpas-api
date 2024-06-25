<?php

namespace App\Http\Controllers;

use App\Models\UserDeletionToken;
use App\Services\CanvasService;
use App\Utils\Token;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class UserDeletionController extends Controller {
    private const VERIFY_TIMEOUT_SECONDS = 60 * 30; // 1 minute * 30 = 30 minutes

    public function getToken() {
        $userId = Arr::get(session()->get("settings"), "custom_canvas_user_id");

        $activeToken = UserDeletionToken::where("canvas_user_id", $userId)
            ->whereNull("canceled_at")
            ->where(function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->whereNull("confirmed_at")
                        ->where("created_at", ">", Carbon::now("UTC")->subSeconds(self::VERIFY_TIMEOUT_SECONDS));
                })
                ->orWhere(function ($innerQuery) {
                    $innerQuery->whereNotNull("confirmed_at");
                });
            })
            ->first();
        if (empty($activeToken)) return response()->json(["message" => "Fant ingen aktiv kode"], 404);

        $createdAtLocal = Carbon::parse($activeToken->created_at)->toDateTimeString();
        $confirmedAtLocal = $activeToken->confirmed_at ? Carbon::parse($activeToken->confirmed_at)->toDateTimeString() : null;
        $deletedAtLocal = $activeToken->deleted_at ? Carbon::parse($activeToken->deleted_at)->toDateTimeString() : null;
        $canceledAtLocal = $activeToken->canceled_at ? Carbon::parse($activeToken->canceled_at)->toDateTimeString() : null;

        return response()->json([
            "message" => null,
            "payload" => [
                "id" => $activeToken->id,
                "canvasId" => $activeToken->canvas_user_id,
                "createdAt" => $createdAtLocal,
                "confirmedAt" => $confirmedAtLocal,
                "canceledAt" => $canceledAtLocal,
                "deletedAt" => $deletedAtLocal
            ]
        ]);
    }

    private function getActiveTokenId() {
        $activeToken = $this->getToken()->getOriginalContent();
        return isset($activeToken["payload"]) ? $activeToken["payload"]["id"] : null;
    }

    public function createToken() {
        $userId = Arr::get(session()->get("settings"), "custom_canvas_user_id");

        $activeTokenId = $this->getActiveTokenId();
        if ($activeTokenId != null) return response()->json(["message" => "Det finnes allerede en aktiv kode"], 409);

        $client = new Client();
        $canvasService = new CanvasService($client);
        $canvasUserData = $canvasService->getUser($userId);

        $userEmail = $canvasUserData->email;
        if (empty($userEmail)) return response()->json(["message" => "Fant ikke epost for canvas bruker"], 422);

        logger("Creating token for user deletion: $userId");
        $token = Token::generate(9);
        $hashedToken = password_hash($token, PASSWORD_BCRYPT, ["cost" => 12]);

        logger("Sending token for user deletion to '$userEmail': $userId");
        Mail::raw("Kode: $token", function ($message) use ($userEmail) {
            $message
                ->to($userEmail)
                ->subject("Kompetanseportalen - Slett Meg");
        });

        UserDeletionToken::create([
            "canvas_user_id" => $userId,
            "token" => $hashedToken
        ]);

        return response()->json(["message" => null]);
    }

    public function verifyToken(Request $request) {
        $userId = Arr::get(session()->get("settings"), "custom_canvas_user_id");

        $emailToken = $request->json("emailToken");
        if(empty($emailToken)) return response()->json(["message" => "Påkrevd verdi mangler: emailToken"], 400);

        $activeTokenId = $this->getActiveTokenId();
        if ($activeTokenId == null) return response()->json(["message" => "Fant ingen aktiv kode med ubekreftet status"], 404);

        $activeToken = UserDeletionToken::where("canvas_user_id", $userId)->where("id", $activeTokenId)->first();
        if (!empty($activeToken->confirmed_at)) return response()->json(["message" => "Fant aktiv kode men den er allerede aktivert"], 422);

        logger("Confirming token for user deletion: $userId");
        $emailTokenIsValid = password_verify($emailToken, $activeToken->token);
        if (!$emailTokenIsValid) return response()->json(["message" => "Ugyldig verifiseringskode"], 400);

        $activeToken->confirmed_at = Carbon::now("UTC");
        $activeToken->save();

        return response()->json(["message" => null]);
    }

    public function cancelToken() {
        $userId = Arr::get(session()->get("settings"), "custom_canvas_user_id");

        $activeTokenId = $this->getActiveTokenId();
        if ($activeTokenId == null) return response()->json(["message" => "Fant ingen aktiv kode"], 404);

        $activeToken = UserDeletionToken::where("canvas_user_id", $userId)->where("id", $activeTokenId)->first();
        if(!empty($activeToken->canceled_at)) return response()->json(["message" => "Fant inaktiv kode men den er allerede kansellert"], 422);

        logger("Cancel token for user deletion: $userId");
        $activeToken->canceled_at = Carbon::now("UTC");
        $activeToken->save();

        return response()->json(["message" => null]);
    }
}
