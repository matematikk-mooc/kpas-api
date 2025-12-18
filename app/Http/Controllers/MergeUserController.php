<?php

namespace App\Http\Controllers;

use App\Models\CanvasUserMergeToken;
use App\Utils\Token;
use App\Repositories\CanvasRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use DateTimeImmutable;

class KpasCanvasCourse
{
    public $courseId;
    public $courseName;

    public function __construct($courseId, $courseName)
    {
        $this->courseId = $courseId;
        $this->courseName = $courseName;
    }
}

class MergeUserController extends Controller
{
    private const CODE_TIMEOUT_SECONDS = 30 * 60;
    private const ID_TOKEN_DELIMITER = '-';

    private $canvasRepository;

    public function __construct(CanvasRepository $canvasRepository)
    {
        $this->canvasRepository = $canvasRepository;
    }

    public function createToken(Request $request)
    {
        $userId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');
        logger("MergeUserController::createToken user_id=$userId");

        $codeEntry = CanvasUserMergeToken::firstOrNew([
            'canvas_user_id' => $userId,
        ]);

        $token = Token::generate(9);
        $hashed = password_hash($token, PASSWORD_BCRYPT, ['cost' => 12]);
        $codeEntry->canvas_user_id = $userId;
        $codeEntry->token = $hashed;
        $codeEntry->save();
        return implode([$userId, self::ID_TOKEN_DELIMITER, $token]);
    }

    public function mergeUser(Request $request)
    {
        $toUserId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');
        logger("MergeUserController::mergeUser user_id=$toUserId");

        $parsedToken = $this->parseToken($request);
        if (is_null($parsedToken)) {
            return response('Ugyldig kode.', 403);
        }

        $fromUserId = $parsedToken->userId;
        if ($fromUserId == $toUserId) {
            return response('Kan ikke slå brukeren sammen med seg selv.', 400);
        }

        if($this->validateToken($fromUserId, $parsedToken->token)) {
            $this->canvasRepository->mergeUsers($fromUserId, $toUserId);
            CanvasUserMergeToken::destroy($fromUserId);

            logger("MergeUserController::mergeUser user_id=$toUserId from_user_id=$fromUserId");
            return response('', 200);
        } else {
            logger("MergeUserController::mergeUser user_id=$toUserId from_user_id=$fromUserId error=invalid token");
            return response('Ugyldig kode.', 403);
        }
    }

    public function getCourseIntersection(Request $request)
    {
        $toUserId = Arr::get(session()->get('settings'), 'custom_canvas_user_id');
        logger("MergeUserController::getCourseIntersection user_id=$toUserId");
        $parsedToken = $this->parseToken($request);

        if (is_null($parsedToken)) {
            return response('Ugyldig kode.', 403);
        }

        $fromUserId = $parsedToken->userId;
        if(!$this->validateToken($fromUserId, $parsedToken->token)) {
            logger("MergeUserController::getCourseIntersection user_id=$toUserId from_user_id=$fromUserId error=invalid token");
            return response('Ugyldig kode.', 403);
        }

        $getCourseIdFun = function ($enrollment) {
            return $enrollment->course_id;
        };

        $fromEnrollments = $this->canvasRepository->getUserEnrollments($parsedToken->userId);
        $toEnrollments = $this->canvasRepository->getUserEnrollments($toUserId);

        $fromCourseIds = array_map($getCourseIdFun, $fromEnrollments);
        $toCourseIds = array_map($getCourseIdFun, $toEnrollments);

        //A user can be enrolled in the same course with different roles.
        $fromCourseIds = array_unique($fromCourseIds);
        $toCourseIds = array_unique($toCourseIds);

        logger("MergeUserController::getCourseIntersection user_id=$toUserId from_user_id=$fromUserId");
        $conflicts = array_values(array_intersect($fromCourseIds, $toCourseIds));

        $course_conflicts = array();
        foreach ($conflicts as $conflict) {
            $canvasCourse = $this->canvasRepository->getCourseById($conflict);
            $conflictCourse = new KpasCanvasCourse($canvasCourse->id, $canvasCourse->name);

            array_push($course_conflicts, $conflictCourse);
        }
        return $course_conflicts;
    }

    private function parseToken(Request $request): ?ParsedToken
    {
        $idToken = $request->header('X-merge-token');

        if (is_null($idToken)) {
            logger("MergeUserController::parseToken user_id=$userId error=missing token");
            return null;
        }

        $tokenArray = explode(self::ID_TOKEN_DELIMITER, $idToken);
        $fromUserId = $tokenArray[0];

        if (!is_numeric($fromUserId)) {
            logger("MergeUserController::parseToken user_id=$userId error=invalid token");
            return null;
        }

        $token = $tokenArray[1];
        return new ParsedToken($fromUserId, $token);
    }

    /**
     * Method to validate tokens, note that it is vulnerable to timing attacks to determine why a token is not valid
     * or if a user id has a token in the DB. This is unlikely to be an issue in this case, but something to be aware of.
     */
    private function validateToken(int $userId, string $token): bool
    {
        $codeEntry = CanvasUserMergeToken::where('canvas_user_id', $userId)->first();

        if (is_null($codeEntry)) {
            logger("MergeUserController::validateToken user_id=$userId error=no token in DB");
            return false;
        }

        $updatedAt = new DateTimeImmutable($codeEntry->updated_at);
        $timestamp = $updatedAt->getTimestamp();
        if ($timestamp < time() - self::CODE_TIMEOUT_SECONDS) {
            logger("MergeUserController::validateToken user_id=$userId error=token out of date");
            $codeEntry->delete();
            return false;
        }

        return password_verify($token, $codeEntry->token);
    }
}

class ParsedToken
{
    public $userId;
    public $token;

    function __construct(int $userId, string $token)
    {
        $this->userId = $userId;
        $this->token = $token;
    }
}