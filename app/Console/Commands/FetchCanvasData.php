<?php

namespace App\Console\Commands;

use App\Exceptions\CanvasException;
use App\Models\CanvasCourse;
use App\Models\Group;
use App\Models\JoinCanvasGroupUser;
use App\Services\CanvasService;
use App\Utils\SentryTrace;

use Illuminate\Console\Command;
use Exception;

class FetchCanvasData extends Command
{
    protected $signature = 'fetch_from:canvas';
    protected $description = 'Fetch data from Canvas to update the local mirroring tables';

    public function handle()
    {
        SentryTrace::new($this->signature, 'console');
        SentryTrace::setContext('monitor', [
            'job_name' => $this->signature,
            'job_description' => $this->description,
            'name' => 'scheduled_artisan-fetch-from-canvas',
            'slug' => 'scheduled_artisan-fetch-from-canvas',
        ]);

        $transaction = SentryTrace::getTransaction();
        SentryTrace::setSpan(null); // ?NOTE: Disable tracing for this command to avoid wasting quota for long running command that runs daily

        try {
            logger()->info('Canvas data synchronization started.');

            $this->processCourses();
            $this->processUserGroupRelationships();

            logger()->info('Canvas data synchronization completed.');

            SentryTrace::setSpan($transaction);
            SentryTrace::finish(null);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            SentryTrace::setSpan($transaction);

            logger()->error('Canvas data synchronization failed.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            Sentry\captureException($e);
            
            SentryTrace::finish(null, 500);
            throw $e;
        }
    }

    private function processCourses()
    {
        logger()->info('Fetching courses from Canvas started.');

        try {
            $canvasService = new CanvasService();
            $coursesFromCanvas = $canvasService->getAllCourses();
            $bulkInsertData = [];

            CanvasCourse::truncate();
            foreach ($coursesFromCanvas as $courseRaw) {
                $bulkInsertData[] = [
                    'canvas_id' => $courseRaw->id,
                    'name' => $courseRaw->name,
                ];
            }

            if (!empty($bulkInsertData)) CanvasCourse::insert($bulkInsertData);

            logger()->info('Fetching courses from Canvas completed.', ['course_count' => count($coursesFromCanvas)]);
        } catch (\Exception $e) {
            Sentry\captureException($e);
            logger()->error('Error fetching courses from Canvas.', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function processUserGroupRelationships()
    {
        $groupIDs = [];
        logger()->info('Fetching user-group relationships from Canvas started.');
    
        try {
            $canvasService = new CanvasService();
            $groupsFromDb = Group::all();
            
            JoinCanvasGroupUser::truncate();
            foreach ($groupsFromDb as $index => $group) {
                $groupID = $group->canvas_id;
                $groupIDs[] = $groupID;

                try {
                    $usersFromCanvas = $canvasService->getUsersInGroup($groupID);
                    $existingRecords = JoinCanvasGroupUser::where('canvas_group_id', $groupID)
                        ->pluck('canvas_user_id')
                        ->toArray();

                    $bulkInsertData = [];
                    foreach ($usersFromCanvas as $user) {
                        if (!in_array($user->id, $existingRecords)) {
                            $bulkInsertData[] = [
                                'canvas_group_id' => $groupID,
                                'canvas_user_id' => $user->id,
                            ];
                        }
                    }

                    if (!empty($bulkInsertData)) JoinCanvasGroupUser::insert($bulkInsertData);
                } catch (CanvasException $e) {
                    logger()->warning('Error fetching users for group.', ['group_id' => $groupID, 'error' => $e->getMessage()]);
                    if (!str_contains($e->getMessage(), "not found")) {
                        Sentry\captureException($e);
                        throw $e;
                    }
                } catch (\Exception $e) {
                    Sentry\captureException($e);
                    logger()->error("Error processing group ID {$groupID}: " . $e->getMessage());
                    throw $e;
                }
            }

            logger()->info('Fetching user-group relationships from Canvas completed.');
        } catch (\Exception $e) {
            Sentry\captureException($e);
            logger()->error('Error fetching user-group relationships from Canvas.', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
