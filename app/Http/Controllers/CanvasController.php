<?php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Services\CanvasService;
use GuzzleHttp\Client;

class CanvasController extends Controller
{
    public function getModuleTitles($courseId)
    {
        $CanvasService = new CanvasService(new Client());
        $modules = $CanvasService->getCourseModules($courseId);
        return new SuccessResponse($this->stripModulesDataToTitles($modules));
    }

    private function stripModulesDataToTitles($modules)
    {
        $titles = [];
        foreach ($modules as $module) {
            if($module->published == true)
                array_push($titles, (object) ['name' => $module->name,
                                'id' => $module->id,
                                'position' => $module->position,
                                'published' => $module->published]);

        }
        return $titles;
    }
}
