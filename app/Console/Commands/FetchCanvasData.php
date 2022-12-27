<?php

namespace App\Console\Commands;

use App\Exceptions\CanvasException;
use App\Models\CanvasCourse;
use App\Models\Group;
use App\Models\JoinCanvasGroupUser;
use App\Services\CanvasService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchCanvasData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch_from:canvas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from Canvas to update the local mirroring tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        logger('Fetching data from Canvas: start');
        $client = new Client();
        $canvasService = new CanvasService($client);


        logger('Fetching data from Canvas: fetch courses');
        $coursesFromCanvas = $canvasService->getAllCourses();
        CanvasCourse::truncate();
        foreach ($coursesFromCanvas as $courseRaw) {
            CanvasCourse::create(['canvas_id' => $courseRaw->id, 'name' => $courseRaw->name]);
        }

        logger('Fetching data from Canvas: fetching user-group-relationships');
        $groupsFromDb = Group::all();
        JoinCanvasGroupUser::truncate();
        foreach ($groupsFromDb as $group) {
            $groupID = $group->canvas_id;
            try {
                $usersFromCanvas = $canvasService->getUsersInGroup($groupID);
            } catch (CanvasException $e) {
                logger('Fetching data from Canvas: error fetching users in group ' . $groupID . ': ' . $e->getMessage());
                continue;
            }
            foreach ($usersFromCanvas as $user) {
                try {
                    JoinCanvasGroupUser::create(['canvas_group_id' => $groupID, 'canvas_user_id' => $user->id]);
                } catch (\Exception $e) {
                    logger('Fetching data from Canvas: error creating join for group ' . $groupID . ' and user ' . $user->id . ": " . $e->getMessage());
                }
            }
        }

        logger('Fetching data from Canvas: end');

        return Command::SUCCESS;
    }
}
