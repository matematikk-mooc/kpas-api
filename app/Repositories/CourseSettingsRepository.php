<?php

namespace App\Repositories;

use App\Models\Filter;
use App\Models\Category;
use App\Models\CourseSettings;
use App\Models\CourseCategory;
use App\Models\CourseFilter;
use \DateTime;
use App\Http\Requests\CourseSettings\CourseSettingsRequest;

class CourseSettingsRepository
{

    public function getFilters()
    {
        return Filter::all();
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function getCategory(int $categoryId)
    {
        return Category::find($categoryId);
    }

    public function getCourseCategory(int $courseId)
    {
        return Category::where('course_id', $courseId)->first();
    }

    public function getCourseFilters(int $courseId)
    {
        return Filter::whereHas('courseFilter', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();
    }

    public function getCourseSettings($courseId)
    {
        return CourseSettings::with([
            'courseCategory',
            'courseCategory.category',
            'courseFilter',
            'courseFilter.filter'
        ])->where('course_id', $courseId)->first();
    }

    public function updateCourseSettings(int $courseId, CourseSettingsRequest $courseSettings)
    {

        CourseSettings::updateOrCreate(
            ['course_id' => $courseId],
            [
                'course_id' => $courseId,
                'unmaintained_since' => new DateTime($courseSettings['unmaintained_since']),
                'role_support' => $courseSettings['role_support'],
                'licence' => $courseSettings['licence'],
                'multilang' => $courseSettings['multilang'],
                'banner_type' => $courseSettings['banner_type'],
                'banner_text' => $courseSettings['banner_text'],
            ]
        );
        $newCourseCategory = $courseSettings['courseCategory'][0];
        $courseCategory = CourseCategory::updateOrCreate(
            ['course_id' => $courseId],
            [
                'category_id' => $newCourseCategory['category_id'],
                'new' => $newCourseCategory['new'],
                'position' => $newCourseCategory['position']
            ]
        );

        $currentCourseFilters = CourseFilter::where('course_id', $courseId)->get();
        $courseFilters = $courseSettings['courseFilters'];

        foreach ($currentCourseFilters as $currentCourseFilter) {
            $courseFilter = in_array($currentCourseFilter->filter_id, array_column($courseFilters, 'filter_id'));
            if (!$courseFilter) {
                $currentCourseFilter->delete();
            }
        }
        foreach ($courseFilters as $courseFilter) {
            $currentCourseFilter = $currentCourseFilters->where('filter_id', $courseFilter['filter_id'])->first();
            if (!$currentCourseFilter) {
                CourseFilter::create([
                    'course_id' => $courseId,
                    'filter_id' => $courseFilter['filter_id'],
                ]);
            }
        }

        return $this->getCourseSettings($courseId);

    }


}
