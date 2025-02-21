<?php
namespace App\Services;

use App\Services\CanvasService;
use App\Models\Kompetansepakke;
use App\Models\Diploma;

class DiplomaV2Service {
    public $userId;
    public $userName;

    public $courseId;
    public $courseName;
    public $courseUserRoles;

    public $courseDiplomaDate;
    public $courseDiplomaLogos;
    public $courseDiplomaDescription;
    public $courseDiplomaBy;
   
    public $principalRoleName = "Skoleleder";
    public $totalRequirements = 0;
    public $totalRequirementsCompleted = 0;
    public $modules = [];

    public function __construct($userId, $userName, $courseId, $courseName, $courseUserRoles, $courseDiplomaLogos) {
        $this->userId = $userId;
        $this->userName = $userName;

        $this->courseId = $courseId;
        $this->courseName = $courseName;
        $this->courseUserRoles = !empty($courseUserRoles) ?  explode(",", $courseUserRoles) : [];

        $kpasCourseSettings = Kompetansepakke::where('course_id', $this->courseId)->first();
        $this->courseDiplomaDate = date("d.m.Y");
        $this->courseDiplomaLogos = !empty($courseDiplomaLogos) && is_array($courseDiplomaLogos) ? $courseDiplomaLogos : [];
        $this->courseDiplomaDescription = !empty($kpasCourseSettings) && !empty($kpasCourseSettings->diplom_beskrivelse) ? $kpasCourseSettings->diplom_beskrivelse : "";
        $this->courseDiplomaBy = !empty($kpasCourseSettings) && !empty($kpasCourseSettings->utviklet_av) ? $kpasCourseSettings->utviklet_av : "";

        $this->principalRoleName = config('canvas.principal_role');
        $this->getCourseProgress();
    }

    private function getCourseProgress() {
        $canvasService = new CanvasService();

        $modulesForUser = $canvasService->getModulesForCourse($this->courseId, $this->userId);
        $lastModuleKey = key(array_slice($modulesForUser, -1, 1, true));

        foreach ($modulesForUser as $moduleKey => $moduleItem) {
            $completedAt = property_exists($moduleItem, 'completed_at') ? $moduleItem->completed_at : null;
            $moduleObject = new DiplomaModule($moduleItem->id, $moduleItem->name,  $completedAt);
            $modulePages = $moduleItem->items;
            $lastPageKey = key(array_slice($modulePages, -1, 1, true));

            foreach ($modulePages as $pageKey => $pageItem) {
                $pageCompleted = false;
                $pageCompletionType = null;
                $pageCompletionRequirement = $pageItem->completion_requirement ?? null;
                $isLastModulesAndPage = false; // Pages should be marked as optional instead of this hotfix as diploma might not be the last page: $moduleKey == $lastModuleKey && $pageKey == $lastPageKey;

                if (!$isLastModulesAndPage && !empty($pageCompletionRequirement)) {
                    $isPageIndented = !empty($pageItem->indent) && $pageItem->indent != 0;
                    $isPrincipal = in_array($this->principalRoleName, $this->courseUserRoles);
                    $includeNormalRequirements = !$isPageIndented;
                    $includeLeaderRequirements = $isPageIndented && $isPrincipal;

                    if ($includeNormalRequirements || $includeLeaderRequirements) {
                        $pageCompleted = $pageCompletionRequirement?->completed == true;
                        if ($pageCompleted) $this->totalRequirementsCompleted++;

                        $pageCompletionType = $pageCompletionRequirement?->type;
                        $this->totalRequirements++;
                    }
                }

                $pageObject = new DiplomaPage($pageItem->id, $pageItem->title, $pageItem->type, $pageItem->indent, $pageCompleted, $pageCompletionType);
                array_push($moduleObject->pages, $pageObject);
            }

            array_push($this->modules, $moduleObject);
        }

        if (!$this->skipValidations() && $this->isCourseCompleted()) $this->storeDiplomaCompletion();
    }

    private function skipValidations() {
        $skipSaveOnRoles = ["TeacherEnrollment", "Udir-Innholdsprodusent", "Udir-forvalter"];
        if (empty(array_intersect($skipSaveOnRoles, $this->courseUserRoles))) return false;
        return true;
    }

    private function storeDiplomaCompletion() {
        Diploma::updateOrCreate(["user_id" => $this->userId], ["course_id" => $this->courseId]);
    }

    public function isCourseCompleted() {
        if ($this->skipValidations()) return true;
        return $this->totalRequirementsCompleted == $this->totalRequirements;
    }

    public function render() {
        logger("getDiplomaHtml");

        return view('main.diploma-v2')
            ->withUserName($this->userName)
            ->withCourseName($this->courseName)
            ->withCourseDiplomaDate($this->courseDiplomaDate)
            ->withCourseDiplomaLogos($this->courseDiplomaLogos)
            ->withCourseDiplomaDescription($this->courseDiplomaDescription)
            ->withCourseDiplomaBy($this->courseDiplomaBy)

            ->withTotalRequirements($this->totalRequirements)
            ->withTotalRequirementsCompleted($this->totalRequirementsCompleted)
            ->withModules($this->modules)

            ->withIsCourseCompleted($this->isCourseCompleted());
    }
}

class DiplomaModule {
    public function __construct(
        public int $id,
        public string $title,
        public ?string $completedAt = null,
        public array $pages = []
    ) {}
}

class DiplomaPage {
    public function __construct(
        public int $id,
        public string $title,
        public string $type,
        public string $indent,
        public bool $completed,
        public ?string $completionType = null
    ) {}
}
