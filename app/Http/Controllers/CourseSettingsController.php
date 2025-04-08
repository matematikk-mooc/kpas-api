<?php

namespace App\Http\Controllers;

use App\Repositories\CourseSettingsRepository;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ErrorResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CourseSettings\CourseSettingsRequest;
use App\Http\Requests\CourseSettings\FilterRequest;
use App\Http\Requests\CourseSettings\CategoryRequest;


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
        return new SuccessResponse($result);

    }

    public function getCourseSettingsForAllCourses(): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getCourseSettingsForAllCourses();
        return new SuccessResponse($result);

    }

    public function getCourseImages(): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getCourseImages();
        return new SuccessResponse($result);

    }

    public function getHighLightedCourse(): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->getHighLightedCourse();
        return new SuccessResponse($result);

    }

    public function addFilter(FilterRequest $filter): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->addFilter($filter);
        return new SuccessResponse($result);

    }

    public function addCategory(CategoryRequest $category): SuccessResponse
    {
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->addCategory($category);
        return new SuccessResponse($result);

    }

    public function updateCourseSettings(CourseSettingsRequest $request, int $courseId)
    {
        if (strlen($request['banner_text']) >= 255) return new ErrorResponse("Banner text should be less than 255 characters", 400);
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->updateCourseSettings($courseId, $request);
        return new SuccessResponse($result);
    }

    public function updateHighlightedCourse(Request $request): SuccessResponse
    {
        logger("updateHighlightedCourse");
        $courseSettingsRepository = new CourseSettingsRepository();
        $result = $courseSettingsRepository->updateHighlightedCourse($request->get('courseId'));
        logger($result);
        return new SuccessResponse($result);

    }

}
