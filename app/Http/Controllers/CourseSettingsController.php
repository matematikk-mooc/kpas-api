<?php

namespace App\Http\Controllers;

use App\Repositories\CourseSettingsRepository;
use App\Http\Responses\SuccessResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CourseSettings\CourseSettingsRequest;


class CourseSettingsController extends Controller
{


    public function getFilters(): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getFilters();
        return new SuccessResponse($result);

    }

    public function getCategories(): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getCategories();
        return new SuccessResponse($result);

    }

    public function getCategory(int $categoryId): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getCategory($categoryId);
        return new SuccessResponse($result);

    }

    public function getCourseCategory(int $courseId): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getCourseCategory($courseId);
        return new SuccessResponse($result);

    }

    public function getCourseFilters(int $courseId): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getCourseFilters($courseId);
        return new SuccessResponse($result);

    }

    public function getCourseSettings(int $courseId): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getCourseSettings($courseId);
        logger($result);
        return new SuccessResponse($result);

    }

    public function updateCourseSettings(CourseSettingsRequest $request, int $courseId): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        logger($request);
        $result = $courseSettingsRepository->updateCourseSettings($courseId, $request);
        return new SuccessResponse($result);

    }

}
