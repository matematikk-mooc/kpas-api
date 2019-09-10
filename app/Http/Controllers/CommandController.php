<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CommandController extends Controller
{

    public function migrate()
    {
        $exitCode = Artisan::call('migrate --force');
        if($exitCode==0) {
            return 'Migration completed!';
        }

        return 'Migration failed';
    }
}
