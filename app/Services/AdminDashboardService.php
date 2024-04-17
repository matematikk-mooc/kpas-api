<?php
namespace App\Services;


class AdminDashboardService
{

    public function getAdminDashboardBlade($settings)
    {
        logger("getAdminDashboardData");

        return view('main.admin-dashboard')->withSettings($settings);
    }

}