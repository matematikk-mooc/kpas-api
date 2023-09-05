<?php

namespace App\Repositories;

use App\Models\Filter;
use App\Models\Category;
use App\Models\CourseSettings;
use App\Models\CourseCategory;
use App\Models\CourseFilter;
use App\Models\CourseImage;
use \DateTime;
use App\Http\Requests\CourseSettings\CourseSettingsRequest;
use App\Http\Requests\CourseSettings\FilterRequest;
use App\Http\Requests\CourseSettings\CategoryRequest;

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

    public function getBannerTypes()
    {
        return array('ALERT', 'NOTIFICATION', 'FEEDBACK', 'UNMAINTAINED','NONE');
    }

    public function getMultilangTypes()
    {
        return array('ALL', 'SE', 'NN', 'NONE');
    }

    public function getFilterTypes()
    {
        return array('CATEGORY', 'TARGET');
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
            'courseFilter.filter',
            'image'
        ])->where('course_id', $courseId)->first();
    }

    public function getCourseSettingsForAllCourses()
    {
        return CourseSettings::with([
            'courseCategory',
            'courseCategory.category',
            'courseFilter',
            'courseFilter.filter',
            'image'
        ])->get();
    }

    public function getCourseImages(){
        return CourseImage::all();
    }

    public function addFilter(FilterRequest $filter)
    {
        $filter = Filter::create([
            'filter_name' => $filter['filter_name'],
            'type' => $filter['type'],
        ]);
        return $filter;
    }

    public function addCategory(CategoryRequest $category)
    {
        $category = Category::create([
            'name' => $category['name'],
            'position' => $category['position'],
            'color_code' => $category['color_code'],
        ]);
        return $category;
    }

    public function updateCourseSettings(int $courseId, CourseSettingsRequest $courseSettings)
    {
        CourseSettings::updateOrCreate(
            ['course_id' => $courseId],
            [
                'course_id' => $courseId,
                'unmaintained_since' => $courseSettings['unmaintained_since']? new DateTime($courseSettings['unmaintained_since']) : null,
                'role_support' => $courseSettings['role_support'],
                'licence' => $courseSettings['licence'],
                'multilang' => $courseSettings['multilang'],
                'banner_type' => $courseSettings['banner_type'],
                'banner_text' => $courseSettings['banner_text'],
                'image_id' => $courseSettings['image_id']
            ]
        );
        if($courseSettings['courseCategory'] != null){
            $newCourseCategory = $courseSettings['courseCategory'][0];
            $courseCategory = CourseCategory::updateOrCreate(
                ['course_id' => $courseId],
                [
                    'category_id' => $newCourseCategory['category_id'],
                    'new' => $newCourseCategory['new']? true : false,
                    'position' => $newCourseCategory['position']
                ]
            );
        }

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
