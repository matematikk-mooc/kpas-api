<?php

namespace App\Http\Controllers;

use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use App\Repositories\CourseSettingsRepository;

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
                "canvas" => false
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
            $courses = $this->canvasService->getAllPublishedCourses();
            $connectionData["integrations"]["canvas"] = true;
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
