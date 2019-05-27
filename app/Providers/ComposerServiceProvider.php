<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        $this->layout();
    }

    private function layout()
    {
    }

}
