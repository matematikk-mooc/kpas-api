<?php
namespace App\Services;


class CourseSettingsService
{

    public function getCourseSettingsBlade($settings) {
        logger("getCourseSettingsData");

        return view('main.coursesettings')->withSettings($settings);
    }

}
