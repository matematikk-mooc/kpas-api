<?php

namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use App\Repositories\CourseSettingsRepository;
use App\Http\Controllers\VimeoController;

class PingController extends Controller
{
    /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;
    }

    public function getPing()
    {
        $connectionError = false;
        $connectionData = [
            "database" => false,
            "integrations" => [
                "canvas" => false,
                "vimeo" => false
            ]
        ];

        try {
            $courseSettingsRepository = new CourseSettingsRepository();
            $courseFilters = $courseSettingsRepository->getFilters();
            $connectionData["database"] = true;
        } catch (\Throwable $th) {
            \Sentry\captureException($th);
            $connectionError = true;
        }

        try {
            $courses = $this->canvasService->getCourseData(360);
            $connectionData["integrations"]["canvas"] = true;
        } catch (\Throwable $th) {
            \Sentry\captureException($th);
            $connectionError = true;
        }

        try {
            $vimeoController = new VimeoController();
            $transcriptTest = $vimeoController->index(604691899);
            $connectionData["integrations"]["vimeo"] = true;
        } catch (\Throwable $th) {
            \Sentry\captureException($th);
            $connectionError = true;
        }

        $statusCode = $connectionError ? 500 : 200;
        \Sentry\addBreadcrumb(
            category: "ping.response",
            message: "Ping response: {$statusCode}",
            metadata: $connectionData,
            level: $connectionError ? "error" : "info"
        );

        if ($connectionError) {
            return response()->json([
                'status' => $statusCode,
                'status_message' => 'Failure',
                'result' => $connectionData
            ], 500);
        }

        return new SuccessResponse($connectionData);
    }
}
