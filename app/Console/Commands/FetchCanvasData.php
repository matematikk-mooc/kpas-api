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
        try {
            SentryTrace::new($this->signature, 'console');
            SentryTrace::setContext('monitor', [
                'job_name' => $this->signature,
                'job_description' => $this->description,
                'name' => 'scheduled_artisan-fetch-from-canvas',
                'slug' => 'scheduled_artisan-fetch-from-canvas',
            ]);
    
            logger()->info('Canvas data synchronization started.');

            $this->processCourses();
            $this->processUserGroupRelationships();

            logger()->info('Canvas data synchronization completed.');

            SentryTrace::finish(null);
            return Command::SUCCESS;
        } catch (\Exception $e) {
            logger()->error('Canvas data synchronization failed.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            SentryTrace::finish(null, 500);
            throw $e;
        }
    }

    private function processCourses()
    {
        $parentSpan = SentryTrace::getSpan();
        SentryTrace::newChild($parentSpan, 'sync', 'Canvas: Courses');
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
            SentryTrace::setData(['courses', $bulkInsertData]);
            SentryTrace::finish($parentSpan);
        } catch (\Exception $e) {
            logger()->error('Error fetching courses from Canvas.', ['error' => $e->getMessage()]);
            SentryTrace::finish($parentSpan, 500);
            throw $e;
        }
    }

    private function processUserGroupRelationships()
    {
        $groupIDs = [];
        $parentSpan = SentryTrace::getSpan();
        $span = SentryTrace::newChild($parentSpan, 'sync', 'Canvas: User-Group Relationships');
        logger()->info('Fetching user-group relationships from Canvas started.');
    
        try {
            $canvasService = new CanvasService();
            $groupsFromDb = Group::all();
            
            JoinCanvasGroupUser::truncate();
            foreach ($groupsFromDb as $index => $group) {
                $groupID = $group->canvas_id;
                $groupIDs[] = $groupID;

                try {
                    SentryTrace::newChild($span, 'process', "Group ID #{$groupID}");
                    $usersFromCanvas = $canvasService->getUsersInGroup($groupID);
                    $bulkInsertData = [];

                    foreach ($usersFromCanvas as $user) {
                        $bulkInsertData[] = [
                            'canvas_group_id' => $groupID,
                            'canvas_user_id' => $user->id,
                        ];
                    }
            
                    if (!empty($bulkInsertData)) JoinCanvasGroupUser::insert($bulkInsertData);

                    SentryTrace::setData(['groupID', $bulkInsertData]);
                    SentryTrace::finish($span);
                } catch (CanvasException $e) {
                    logger()->warning('Error fetching users for group.', ['group_id' => $groupID, 'error' => $e->getMessage()]);
                    if (!str_contains($e->getMessage(), "not found")) {
                        SentryTrace::finish($span, 500);
                        throw $e;
                    }

                    SentryTrace::finish($span, 404);
                } catch (\Exception $e) {
                    logger()->error("Error processing group ID {$groupID}: " . $e->getMessage());
                    SentryTrace::finish($span, 500);
                    throw $e;
                }
            }

            logger()->info('Fetching user-group relationships from Canvas completed.');
            SentryTrace::setData(['groupIDs', $groupIDs]);
            SentryTrace::finish($parentSpan);
        } catch (\Exception $e) {
            SentryTrace::setSpan($span);
            logger()->error('Error fetching user-group relationships from Canvas.', ['error' => $e->getMessage()]);
            SentryTrace::finish($parentSpan, 500);
            throw $e;
        }
    }
}
