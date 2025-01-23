<?php

namespace App\Http\Controllers;

use DOMNode;
use DOMElement;
use DOMDocument;
use DOMXPath;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;

use App\Services\CanvasService;
use App\Repositories\CourseSettingsRepository;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ErrorResponse;

class ExportController extends Controller {
    /**
     * @var CanvasService
     */
    protected $canvasService;

    /**
     * @var BffController
     */
    protected $bffController;

    public function __construct(CanvasService $canvasService, BffController $bffController) {
        $this->canvasService = $canvasService;
        $this->bffController = $bffController;
    }

    public function getCourses(): SuccessResponse {
        $publishedCourses = $this->canvasService->getAllPublishedCourses();
        $courses = $this->bffController->filterCoursesByAccountIds($publishedCourses);
        $courseIds = [];

        foreach ($courses as $courseData) {
            if (is_object($courseData) && isset($courseData->course->id)) {
                $courseIds[] = $courseData->course->id;
            }
        }

        return new SuccessResponse($courseIds);
    }

    public function getCourse(int $courseId): SuccessResponse {
        $canvasCourse = $this->canvasService->getCourse($courseId);
        $canvasCourseId = $canvasCourse->id;

        $courseFrontpage = $this->canvasService->getCourseFrontpage($canvasCourseId);
        $courseFrontpageSlugID = isset($courseFrontpage->page_id) ? $courseFrontpage->url : null;

        $courseSettingsRepository = new CourseSettingsRepository();
        $courseSettings = $courseSettingsRepository->getCourseSettings($canvasCourseId);
        $courseSettingsArray = $courseSettings != null ? $courseSettings->toArray() : [];
        $featuredCourse = $courseSettingsRepository->getHighLightedCourse();

        $isFeaturedCourse = $featuredCourse != null && $featuredCourse->course_id == $canvasCourseId;
        $showNewBanner = $courseSettingsArray != null && $courseSettingsArray["course_category"]["new"] == 1;
        $showMoocLicense = $courseSettingsArray != null && $courseSettingsArray["licence"] == 1;
        $hideIndentedContentForLeaders = $courseSettingsArray != null && $courseSettingsArray["role_support"] == 1;
        $unmaintainedSince = $courseSettingsArray != null ? $courseSettingsArray["unmaintained_since"] : null;
        $bannerType = isset($courseSettingsArray["banner_type"]) && $courseSettingsArray["banner_type"] != "NONE" ? $courseSettingsArray["banner_type"] : null;
        $bannerText = $courseSettingsArray["banner_text"] ?? "";
        $imageIllustrationUrl = $courseSettingsArray["image"]["path"] ?? null;

        $languageSetting = $courseSettingsArray != null ? $courseSettingsArray["multilang"] : null;
        $languages = $this->getLanguages($languageSetting);

        $categories = [];
        $courseCategory = $courseSettingsArray["course_category"]["category"] ?? null;
        $courseCategoryName = $courseCategory != null ? $courseCategory["name"] : null;
        $courseCategoryColor = $courseCategory != null ? $courseCategory["color_code"] : null;
        $courseFilters = $courseSettingsArray["course_filter"] ?? [];

        foreach ($courseFilters as $courseFilterItem) {
            $isMainCategory = $courseFilterItem["filter"]["filter_name"] == $courseCategoryName;
            $categoryType = $courseFilterItem["filter"]["type"] == "TARGET" ? "group" : "subject";

            $categories[] = [
                "id" => $courseFilterItem["filter_id"],
                "order" => 0,
                "title" => $courseFilterItem["filter"]["filter_name"],
                "slug" => $this->getSlug($courseFilterItem["filter"]["filter_name"]),
                "type" => $categoryType,
                "isPrimary" => $isMainCategory,
                "color" => $isMainCategory ? $this->getThemeColor($courseCategoryColor) : null,
            ];
        }

        $returnObject = [
            "id" => $canvasCourse->id,
            "frontpageSlugID" => $courseFrontpageSlugID,
            "title" => $canvasCourse->name,
            "slug" => $this->getSlug($canvasCourse->name),
            "description" => $canvasCourse->public_description ?? "",
            "createdAt" => $canvasCourse->created_at,
            "isFeatured" => $isFeaturedCourse,
            "showNewBanner" => $showNewBanner,
            "showMoocLicense" => $showMoocLicense,
            "hideIndentedContentForLeaders" => $hideIndentedContentForLeaders,
            "unmaintainedSince" => $unmaintainedSince,
            "fallbackLanguage" => "nb",
            "languages" => $languages,
            "banner" => $bannerType != null ? [
                "type" => strtolower($bannerType),
                "message" => $bannerText,
            ] : null,
            "images" => $imageIllustrationUrl != null ? [
                [
                    "type" => "illustration",
                    "url" => $imageIllustrationUrl,
                    "alt" => "",
                ]
            ] : [],
            "categories" => $categories,
        ];

        return new SuccessResponse($returnObject);
    }

    private function getLanguages(string $languageSetting): array {
        $languages = ["nb"];
        if ($languageSetting == "ALL") {
            $languages = ["nb", "nn", "se"];
        } else if ($languageSetting == "NN") {
            $languages = ["nb", "nn"];
        } else if ($languageSetting == "SE") {
            $languages = ["nb", "se"];
        }

        return $languages;
    }

    private function getThemeColor(string $themeName): string {
        switch ($themeName) {
            case "theme_0":
                return "#e3f2eb";
            case "theme_1":
                return "#ef9a9a";
            case "theme_2":
                return "#eaeaf5";
            case "theme_3":
                return "#c7c9e3";
            case "theme_4":
                return "#f5f7f9";
            case "theme_5":
                return "#a0b1bf";
            case "theme_6":
                return "#ffebee";
            case "theme_7":
                return "#ffcdd2";
            case "theme_8":
                return "#ffcc80";
            case "theme_9":
                return "#fff3e0";
        }

        return null;
    }

    public function getCourseGroups(int $courseId): SuccessResponse {
        $userGroups = [];

        $canvasCourse = $this->canvasService->getCourse($courseId);
        $canvasCourseId = $canvasCourse->id;

        $courseUserGroups = $this->canvasService->getGroupCategories($canvasCourseId) ?? [];
        foreach ($courseUserGroups as $courseUserGroup) {
            $userGroups[] = [
                "id" => $courseUserGroup->id,
                "title" => $courseUserGroup->name,
            ];
        }

        return new SuccessResponse($userGroups);
    }

    public function getCourseGroupCategories(int $courseId, int $groupId): SuccessResponse {
        $groups = [];

        $canvasCourse = $this->canvasService->getCourse($courseId);
        $canvasCourseId = $canvasCourse->id;

        $courseGroups = $this->canvasService->getGroups($groupId) ?? [];
        foreach ($courseGroups as $courseGroup) {
            $groupCourseId = $courseGroup->course_id;
            if ($groupCourseId != $canvasCourseId) continue; 

            $groups[] = [
                "id" => $courseGroup->id,
                "title" => $courseGroup->name,
                "description" => $courseGroup->description,
                "membersCount" => $courseGroup->members_count,
            ];
        }

        return new SuccessResponse($groups);
    }

    public function getCourseGroupCategoryUsers(Request $request, int $courseId, int $groupId, int $groupCategoryId) {
        $groupUserIds = [];
        $page = $request->query('page');
        $perPage = (int) $request->query('per_page', 50);
        $canvasToken = $request->header('Authorization');
        if (empty($canvasToken)) return new ErrorResponse('Missing Authorization header', 401);
        if ($perPage > 100) return new ErrorResponse('Per page limit is 100', 400);

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;

            $res = $this->canvasService->getGroupMemberships($groupCategoryId, $perPage, false, $canvasToken, $page) ?? [];
            $nextPage = $res['nextPage'] ?? null;
            $groupMemberships = $res['data'] ?? [];

            foreach ($groupMemberships as $groupMembership) {
                $groupUserIds[] = $groupMembership->user_id;
            }

            return new SuccessResponse(['nextPage' => $nextPage, 'users' => $groupUserIds]);
        } catch (ClientException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                return new ErrorResponse('Token is invalid', 401);
            }
        
            throw $e;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getCourseModules(Request $request, int $courseId): SuccessResponse {
        $modules = [];

        $canvasCourse = $this->canvasService->getCourse($courseId);
        $canvasCourseId = $canvasCourse->id;

        $courseSettingsRepository = new CourseSettingsRepository();
        $courseSettings = $courseSettingsRepository->getCourseSettings($canvasCourseId);
        $courseSettingsArray = $courseSettings != null ? $courseSettings->toArray() : [];

        $languageSetting = $courseSettingsArray != null ? $courseSettingsArray["multilang"] : null;
        $languages = $this->getLanguages($languageSetting);

        $courseModules = $this->canvasService->getCourseModules($canvasCourseId, true);
        foreach ($courseModules as $courseModule) {
            $moduleId = $courseModule->id;
            $moduleItems = [];

            $courseModuleItems = $courseModule->items ?? [];
            foreach ($courseModuleItems as $courseModuleItem) {
                $moduleType = strtolower($courseModuleItem->type);
                $moduleItemRequirement = null;

                if (isset($courseModuleItem->completion_requirement)) {
                    $moduleItemRequirementType = $courseModuleItem->completion_requirement->type;

                    if ($moduleItemRequirementType == "must_view") {
                        $moduleItemRequirement = "view";
                    } else if ($moduleItemRequirementType == "must_mark_done") {
                        $moduleItemRequirement = "mark";
                    }
                }

                $returnModuleItem = [
                    "id" => $courseModuleItem->id,
                    "slugID" => $courseModuleItem->page_url ?? null,
                    "order" => $courseModuleItem->position,
                    "indent" => $courseModuleItem->indent,
                    "title" => [
                        "nb" => $courseModuleItem->title,
                    ],
                    "slug" => [
                        "nb" => $this->getSlug($courseModuleItem->title),
                    ],
                    "type" => $moduleType,
                    "requirement" => $moduleItemRequirement,
                ];

                foreach ($languages as $language) {
                    $langTitle = $this->getLangTitle($courseModuleItem->title, $language);
                    $returnModuleItem["title"][$language] = $langTitle;
        
                    $langSlug = $this->getSlug($langTitle);
                    $returnModuleItem["slug"][$language] = $langSlug;
                }

                $moduleItems[] = $returnModuleItem;
            }

            $returnModule = [
                "id" => $moduleId,
                "order" => $courseModule->position,
                "title" => [
                    "nb" => $courseModule->name,
                ],
                "slug" => [
                    "nb" => $this->getSlug($courseModule->name),
                ],
                "items" => $moduleItems,
            ];

            foreach ($languages as $language) {
                $langTitle = $this->getLangTitle($courseModule->name, $language);
                $returnModule["title"][$language] = $langTitle;

                $langSlug = $this->getSlug($langTitle);
                $returnModule["slug"][$language] = $langSlug;
            }

            $modules[] = $returnModule;
        }

        return new SuccessResponse($modules);
    }

    public function getCoursePage(Request $request, int $courseId, string $pagePath): SuccessResponse {
        $canvasCourse = $this->canvasService->getCourse($courseId);
        $canvasCourseId = $canvasCourse->id;

        $courseSettingsRepository = new CourseSettingsRepository();
        $courseSettings = $courseSettingsRepository->getCourseSettings($canvasCourseId);
        $courseSettingsArray = $courseSettings != null ? $courseSettings->toArray() : [];

        $languageSetting = $courseSettingsArray != null ? $courseSettingsArray["multilang"] : null;
        $languages = $this->getLanguages($languageSetting);

        $page = $this->canvasService->getCoursePageContentByPath($canvasCourseId, $pagePath);
        $returnObject = [
            "id" => $page->page_id,
            "title" => [
                "nb" => $page->title
            ],
            "slug" => [
                "nb" => $page->url
            ],
            "isFrontpage" => $page->front_page == true,
            "createdAt" => $page->created_at,
            "updatedAt" => $page->updated_at,
            "content" => [
                "nb" => $page->body,
            ],
        ];

        foreach ($languages as $language) {
            $langTitle = $this->getLangTitle($page->title, $language);
            $returnObject["title"][$language] = $langTitle;

            $langSlug = $this->getSlug($langTitle);
            $returnObject["slug"][$language] = $langSlug;

            // $langHtml = $this->getLangHtml($pageHtml, $language);
            // $returnObject["content"][$language] = $langHtml;
        }

        return new SuccessResponse($returnObject);
    }

    private function getSlug(string $title): string {
        $slug = mb_strtolower($title, 'UTF-8');
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        return $slug;
    }

    private function getLangTitle(string $title, string $langCode): string {
        $pattern = "/\b" . preg_quote($langCode, '/') . ":(.*?)(?=\|[a-z]{2}:|$)/i";
        if (preg_match($pattern, $title, $matches)) return trim($matches[1]);
        if ($langCode == "nb") return $title;
        return '';
    }
    
    // TODO: Add support for multiple languages
    // TODO: Add support for content blocks instead of raw HTML
    private function getLangHtml($html, $keepLang = 'nb') {
        return $html;
    }

    public function getCourseAnnouncements(int $courseId): SuccessResponse {
        $announcements = [];

        $canvasCourse = $this->canvasService->getCourse($courseId);
        $canvasCourseId = $canvasCourse->id;

        $courseAnnouncements = $this->canvasService->getAnnouncements($canvasCourseId);
        foreach ($courseAnnouncements as $announcementItem) {
            $announcements[] = [
                "id" => $announcementItem->id,
                "order" => $announcementItem->position,
                "title" => $announcementItem->title,
                "slug" => $this->getSlug($announcementItem->title),
                "createdAt" => $announcementItem->posted_at,
                "content" => [
                    'nb' => $this->getLangHtml($announcementItem->message, 'nb')
                ]
            ];
        }

        return new SuccessResponse($announcements);
    }

    public function getCourseEnrollments(Request $request, int $courseId) {
        $enrollments = [];
        $page = $request->query('page');
        $perPage = (int) $request->query('per_page', 10);
        $useRealData = $request->query('live', false) === 'true';
        $canvasToken = $request->header('Authorization');
        if (empty($canvasToken)) return new ErrorResponse('Missing Authorization header', 401);
        if ($perPage > 25) return new ErrorResponse('Per page limit is 25', 400);

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;

            $res = $this->canvasService->getCourseEnrollments($canvasCourseId, $perPage, false, $canvasToken, $page);
            $nextPage = $res['nextPage'] ?? null;
            $courseEnrollments = $res['data'] ?? [];

            foreach ($courseEnrollments as $enrollment) {
                $userId = $enrollment->user_id;
                $userIdInLetters = ucfirst($this->numberToLetterDigits("$userId"));

                $modulesProgress = [];
                $modules = $this->canvasService->getModulesWithProgress($canvasCourseId, $userId);
                foreach ($modules as $module) {
                    $moduleId = $module->id;
                    $moduleItems = $module->items ?? [];
                    $moduleItemsProgress = [];

                    foreach ($moduleItems as $moduleItem) {
                        $moduleType = strtolower($moduleItem->type);
                        $moduleItemRequirement = null;
                        $requirementExists = isset($moduleItem->completion_requirement);

                        if ($requirementExists) {
                            $moduleItemRequirementType = $moduleItem->completion_requirement->type;

                            if ($moduleItemRequirementType == "must_view") {
                                $moduleItemRequirement = "view";
                            } else if ($moduleItemRequirementType == "must_mark_done") {
                                $moduleItemRequirement = "mark";
                            }
                        }

                        $moduleItemsProgress[] = [
                            "id" => $moduleItem->id,
                            "requirement" => $moduleItemRequirement,
                            "completed" => $requirementExists ? $moduleItem->completion_requirement->completed : true,
                        ];
                    }

                    $modulesProgress[] = [
                        "id" => $moduleId,
                        "completedAt" => $module->completed_at,
                        "items" => $moduleItemsProgress,
                    ];
                }

                $enrollments[] = [
                    'id' => $enrollment->id,
                    'type' => $enrollment->type,
                    'role' => strtolower($enrollment->role) == "skoleleder" ? "leader" : "teacher",
                    'createdAt' => $enrollment->created_at,
                    'updatedAt' => $enrollment->updated_at,
                    'user' => [
                        'id' => $userId,
                        'name' => $useRealData ? $enrollment->user->name : "John $userIdInLetters",
                        'email' => $useRealData ? $enrollment->user->login_id : "$userIdInLetters@test.kpas.no",
                        'createdAt' => $enrollment->user->created_at,
                    ],
                    "modules" => $modulesProgress
                ];
            }

            return new SuccessResponse(['nextPage' => $nextPage, 'enrollments' => $enrollments]);
        } catch (ClientException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                return new ErrorResponse('Token is invalid', 401);
            }
        
            throw $e;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function numberToLetterDigits($number) {
        $digits = str_split((string)$number);
        $result = '';
    
        foreach ($digits as $digit) {
            if ($digit == '0') {
                $result .= 'x';
                continue;
            }

            $result .= chr($digit + 96);
        }
    
        return $result;
    }
}
