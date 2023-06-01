<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{
    public function run_scheduler()
    {
        Artisan::call('schedule:run');
        return 'OK';
    }
}
