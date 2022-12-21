<?php

namespace App\Console\Commands;

use App\Models\CanvasCourse;
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
        logger('Fetching data from Canvas');
        $client = new Client();
        $canvasService = new CanvasService($client);

        $coursesFromCanvas = $canvasService->getAllCourses();

        CanvasCourse::truncate();
        foreach ($coursesFromCanvas as $courseRaw) {
            CanvasCourse::create(['canvas_id' => $courseRaw->id, 'name' => $courseRaw->name]);
        }

        return Command::SUCCESS;
    }
}
