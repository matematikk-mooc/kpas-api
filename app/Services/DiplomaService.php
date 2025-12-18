<?php
namespace App\Services;

use App\Services\CanvasService;
use App\Exceptions\CanvasException;
use App\Models\Kompetansepakke;
use App\Models\Diploma;

class DiplomaService
{
    public function getDiplomaHtml($settings, $downloadLink, $hasDeservedDiploma)
    {
        $diplomaDisplayName = $settings['custom_canvas_user_display_name'];
        $diplomaCourseName = $settings['custom_canvas_course_name'];
        $logoList = $settings['custom_diploma_logo_list'];
        $diplomaDate=date("d.m.Y") ;

        $courseId = $settings['custom_canvas_course_id'];

        $kompetansepakke = Kompetansepakke::where('course_id', $courseId)->first();
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

    private function hasCompletedModule($module, $isLastModule, $bIncludeIndentedItems)
    {
        $items = $module->items;

        $moduleCompleted = true;
        $noOfItems = count($items);

        for ($j = 0; $j < $noOfItems; $j++) {
            $isLastItem = ($j == ($noOfItems-1));
            $item = $items[$j];

            //It the item is indented and it should not be counted (i.e. normal participant), we should not check completion requirement.
            if ($item->indent && !$bIncludeIndentedItems) {
                continue;
            }
            //The last item in the last module should be the diploma item, in that case we don't check for completion.
            if($isLastModule && $isLastItem)
            {
                continue;
            }

            if (property_exists($item, "completion_requirement")) {
                if (!$item->completion_requirement->completed) {
                    $moduleCompleted = false;
                    break;
                }
            }
        }
        return $moduleCompleted;
    }

    private function findLastModuleNo($modules)
    {
        $lastModuleNo = 0;
        $noOfModules = count($modules);

        for ($i = 0; $i < $noOfModules; $i++) {
            $module = $modules[$i];
            if($module->published) {
                $lastModuleNo = $i;
            }
        }
        return $lastModuleNo;
    }

    private function storeDiplomaCompletionForUser($userId, $courseId)
    {
        Diploma::updateOrCreate(['user_id' => $userId],['course_id' => $courseId]);
    }

    public function hasDeservedDiploma($settings)
    {
        $courseId = $settings["custom_canvas_course_id"];
        $userId = $settings["custom_canvas_user_id"];
        $canvas_service = new CanvasService();


        $principalRoleName = config('canvas.principal_role');
        $enrollments = $settings['custom_canvas_roles'];

        if(str_contains($enrollments, "TeacherEnrollment")) {
            return true;
        }
        if(str_contains($enrollments, "Account Admin")) {
            return true;
        }
        if(str_contains($enrollments, "Udir-Innholdsprodusent")) {
            return true;
        }
        if(str_contains($enrollments, "Udir-forvalter")) {
            return true;
        }

        $bIncludeIndentedItems = str_contains($enrollments, $principalRoleName);
        $modules = $canvas_service->getModulesForCourse($courseId, $userId);

        $hasCompletedAllModules = true;
        $noOfModules = count($modules);

        $lastModuleNo = $this->findLastModuleNo($modules);

        for ($i = 0; $i < $noOfModules; $i++) {
            $module = $modules[$i];
            if($module->published) {
                $isLastModule = ($i == $lastModuleNo);
                if(!$this->hasCompletedModule($module, $isLastModule, $bIncludeIndentedItems)) {
                    $hasCompletedAllModules = false;
                    break;
                }
            }
        }
        if($hasCompletedAllModules) {
            $this->storeDiplomaCompletionForUser($userId, $courseId);
        }

        return $hasCompletedAllModules;
    }
}
