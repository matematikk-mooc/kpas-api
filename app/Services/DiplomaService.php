<?php
use App\Services\CanvasService;
use GuzzleHttp\Client;
use App\Exceptions\CanvasException;
use App\Kompetansepakke;

namespace App\Services;

class DiplomaService
{
    public function getDiplomaHtml($settings, $downloadLink, $hasDeservedDiploma) {
        logger("getDiplomaHtml");
        logger("Downloadlink:" . $downloadLink);
        logger($settings);
        $diplomaDisplayName = $settings['custom_canvas_user_display_name'];
        $diplomaCourseName = $settings['custom_canvas_course_name'];
        $logoList = $settings['custom_diploma_logo_list'];
        $diplomaDate=date("d.m.Y") ;

        $courseId = $settings['custom_canvas_course_id'];

        $kompetansepakke = \App\Kompetansepakke::where('course_id', $courseId)->first();
        $diplomaCourseDescription = $kompetansepakke ? $kompetansepakke->diplom_beskrivelse : "";
        $diplomaDeliveredBy = $kompetansepakke ? $kompetansepakke->utviklet_av : "";
    
        return view('main.diploma')
            ->withDiplomaName($diplomaDisplayName)
            ->withDiplomaCourseName($diplomaCourseName)
            ->withDiplomaCourseDescription($diplomaCourseDescription)
            ->withDiplomaDeliveredBy($diplomaDeliveredBy)
            ->withDiplomaDate($diplomaDate)
            ->withLogoList($logoList)
            ->withDownloadLinkOn($downloadLink)
            ->withHasDeservedDiploma($hasDeservedDiploma);
    }

    public function hasDeservedDiploma($settings) {
        logger("hasDeservedDiploma BEGIN");
        logger($settings);

        $courseId = $settings["custom_canvas_course_id"];
        $userId = $settings["custom_canvas_user_id"];
        $client = new \GuzzleHttp\Client();
        $canvas_service = new CanvasService($client);


        $principalRoleName = config('canvas.principal_role');
        $enrollments = $settings['custom_canvas_roles'];
        $bIncludeIndentedItems = str_contains($enrollments, $principalRoleName);

        $modules = $canvas_service->getModulesForCourse($courseId, $userId);
        $total = 0;
        $completed = 0;

        $noOfModules = count($modules);
        for ($i = 0; $i < $noOfModules; $i++) {
            $module = $modules[$i];
            $isLastModule = ($i == ($noOfModules-1));
            $items = $module->items;

            $noOfItems = count($items);
            for ($j = 0; $j < $noOfItems; $j++) {
                $isLastItem = ($j == ($noOfItems-1));
                $item = $items[$j];
                if (!($item->indent && !$bIncludeIndentedItems)) {
                    logger("IsLastModule:" . ($isLastModule ? "true" : "false") . " IsLastItem:" . ($isLastItem ? "true" : "false"));
                    if (property_exists($item, "completion_requirement") && !($isLastModule && $isLastItem)) {
                        $total++;
                        if ($item->completion_requirement->completed) {
                            $completed++;
                        }
                    }
                }
            }
        }
        logger("Completed:" . $completed . " Total:" . $total);
        return $completed == $total; 
    }
}