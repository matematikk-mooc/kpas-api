<?php

namespace App\LTI;

use Illuminate\Support\Facades\Log;
use IMSGlobal\LTI\ToolProvider\ToolProvider;

class CustomToolProvider extends ToolProvider
{
    public function onLaunch(): void
    {
        session(['settings' => $this->resourceLink->getSettings()]);
    }

    public function onContentItem(): void
    {

    }

    public function onError(): void
    {
        Log::error($this->reason);
        die($this->reason);
    }

    public function onRegister(): void
    {

    }
}
