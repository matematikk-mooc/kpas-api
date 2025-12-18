<?php
namespace App\Services;


class AdminDashboardService
{

    public function getAdminDashboardBlade($settings)
    {
        logger("AdminDashboardService::getAdminDashboardBlade");

        return view('main.admin-dashboard')->withSettings($settings);
    }

}