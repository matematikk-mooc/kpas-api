<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{

    public function migrate()
    {
        $exitCode = Artisan::call('migrate --force');
        if ($exitCode == 0) {
            return 'Migration completed!';
        }

        return 'Migration failed';
    }

    public function run_scheduler()
    {
        Artisan::call('schedule:run');
        return 'OK';
    }

    public function run_fetch_nsr()
    {
        Artisan::call('fetch_from:nsr');
        return 'OK';
    }
}
