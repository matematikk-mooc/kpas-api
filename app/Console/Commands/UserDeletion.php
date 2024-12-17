<?php

namespace App\Console\Commands;

use App\Models\UserDeletionToken;
use App\Services\CanvasService;

use Carbon\Carbon;
use Illuminate\Console\Command;

class UserDeletion extends Command
{
    private const DELETE_TIMEOUT_SECONDS = 86400 * 30; // 1 day * 30 = 30 days

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "kpas:user_deletion {--prompts=true}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "List all users who have request to be deleted and await for admin confirmation";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("{$this->signature} - Starting...");
        $confirmedDeleteTokens = UserDeletionToken::where("confirmed_at", "<", Carbon::now("UTC")->subSeconds(self::DELETE_TIMEOUT_SECONDS))
            ->whereNull("canceled_at")
            ->whereNull("deleted_at")
            ->get();

        if(count($confirmedDeleteTokens) <= 0) {
            $this->info("{$this->signature} - No users found for deletion.");
            return;
        }

        $this->info("{$this->signature} - List all users that are scheduled to be deleted:\n");
        $canvasService = new CanvasService();

        $usersToDelete = [];
        foreach ($confirmedDeleteTokens as $key => $confirmedDeleteTokensItem) {
            $tokenId = $confirmedDeleteTokensItem->id;
            $tokenCanvasId = $confirmedDeleteTokensItem->canvas_user_id;

            try {
                $canvasUserData = $canvasService->getUser($tokenCanvasId);
                if (!empty($canvasUserData)) {
                    array_push(
                        $usersToDelete,
                        [
                            "ID" => $tokenId,
                            "CanvasID" => $canvasUserData->id,
                            "Email" => $canvasUserData->email,
                            "CanceledAt" => $confirmedDeleteTokensItem->canceled_at,
                            "ConfirmedAt" => $confirmedDeleteTokensItem->confirmed_at,
                        ]
                    );
                }
            } catch (\Throwable $th) {
                $this->warn("  - Failed to fetch canvas user data (ID: {$tokenId}, CanvasID: {$tokenCanvasId})\n    - {$th->getMessage()}");
            }
        }

        $headers = ["ID", "CanvasID", "Email", "CanceledAt", "ConfirmedAt"];
        $this->table($headers, $usersToDelete);

        $paramPrompts = $this->option("prompts") == "true";
        if ($paramPrompts) {
            $promptConfirmDeletion = $this->confirm("Do you want to delete all users listed?");

            if (!$promptConfirmDeletion) {
                $this->info("Operation rejected!");
                return;
            }
        }

        $this->info("{$this->signature} - Deleting all users listed above:");
        foreach ($usersToDelete as $key => $usersToDeleteItem) {
            $tokenId = $usersToDeleteItem["ID"];
            $userId = $usersToDeleteItem["CanvasID"];
            $userEmail = $usersToDeleteItem["Email"];

            try {
                $canvasService->deleteUser($userId);

                $this->info("  - Successfully deleted $userEmail from Canvas (CanvasID: {$userId})");

                $activeToken = UserDeletionToken::where("id", $tokenId)->first();
                $activeToken->deleted_at = Carbon::now("UTC");
                $activeToken->save();
            } catch (\Throwable $th) {
                $this->warn("  - Failed deleting {$userEmail} from Canvas (CanvasID: {$userId})\n    - {$th->getMessage()}");
            }
        }
    }
}
