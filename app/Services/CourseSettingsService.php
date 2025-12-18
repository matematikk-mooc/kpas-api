<?php
namespace App\Services;


class CourseSettingsService
{

    public function getCourseSettingsBlade($settings)
    {
        logger("CourseSettingsService::getCourseSettingsBlade");

        return view('main.coursesettings')->withSettings($settings);
    }

}
