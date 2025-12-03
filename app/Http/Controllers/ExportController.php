<?php

namespace App\Http\Controllers;

use DOMNode;
use DOMElement;
use DOMDocument;
use DOMXPath;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
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

    public function getCourses() {
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

    public function getCourse(int $courseId) {
        $canvasCourse = null;
        $canvasCourseId = null;

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

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

    public function getCourseGroups(int $courseId) {
        $userGroups = [];
        $canvasCourse = null;
        $canvasCourseId = null;

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

        $courseUserGroups = $this->canvasService->getGroupCategories($canvasCourseId) ?? [];
        foreach ($courseUserGroups as $courseUserGroup) {
            $userGroups[] = [
                "id" => $courseUserGroup->id,
                "title" => $courseUserGroup->name,
            ];
        }

        return new SuccessResponse($userGroups);
    }

    public function getCourseGroupCategories(int $courseId, int $groupId) {
        $groups = [];
        $canvasCourse = null;
        $canvasCourseId = null;
        $courseGroups = null;

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

        try {
            $courseGroups = $this->canvasService->getGroups($groupId) ?? [];
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course group not found', 404);

            throw $th;
        }

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
        $page = intval($request->query('page'));
        $perPage = (int) $request->query('per_page', 50);
        $canvasToken = $request->header('Authorization');
        if (empty($canvasToken)) return new ErrorResponse('Missing Authorization header', 401);
        if ($perPage > 500) return new ErrorResponse('Per page limit is 500', 400);

        $canvasCourse = null;
        $canvasCourseId = null;

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;
        } catch (ClientException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                return new ErrorResponse('Token is invalid', 401);
            } else if (str_contains($e->getMessage(), 'not found')) {
                return new ErrorResponse('Course not found', 404);
            }

            throw $e;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

        try {
            $res = $this->canvasService->getGroupMemberships($groupCategoryId, $perPage, false, $canvasToken, $page) ?? [];
            $nextPage = $res['nextPage'] ?? null;
            $groupMemberships = $res['data'] ?? [];

            foreach ($groupMemberships as $groupMembership) {
                $groupUserIds[] = $groupMembership->user_id;
            }

            return new SuccessResponse(['nextPage' => $nextPage, 'users' => $groupUserIds]);
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course group category not found', 404);

            throw $th;
        }
    }

    public function getCourseModules(Request $request, int $courseId) {
        $modules = [];
        $canvasCourse = null;
        $canvasCourseId = null;

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

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

    public function getCoursePage(Request $request, int $courseId, string $pagePath) {
        $canvasCourse = null;
        $canvasCourseId = null;

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

        $courseSettingsRepository = new CourseSettingsRepository();
        $courseSettings = $courseSettingsRepository->getCourseSettings($canvasCourseId);
        $courseSettingsArray = $courseSettings != null ? $courseSettings->toArray() : [];

        $languageSetting = $courseSettingsArray != null ? $courseSettingsArray["multilang"] : null;
        $languages = $this->getLanguages($languageSetting);
        $page = null;

        try {
            $page = $this->canvasService->getCoursePageContentByPath($canvasCourseId, $pagePath);
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course page not found', 404);

            throw $th;
        }

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

    public function getCourseAnnouncements(int $courseId) {
        $announcements = [];
        $canvasCourse = null;
        $canvasCourseId = null;

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

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
        $page = intval($request->query('page'));
        $useRealData = $request->query('live', false) === 'true';

        $perPage = (int) $request->query('per_page', 10);
        if ($perPage > 100) return new ErrorResponse('Per page limit is 100', 400);

        $canvasDomain = config('canvas.domain');
        $canvasToken = $request->header('Authorization');
        if (empty($canvasToken)) return new ErrorResponse('Missing Authorization header', 401);

        $canvasCourse = null;
        $canvasCourseId = null;
        $courseSettingsArray = [];

        try {
            $canvasCourse = $this->canvasService->getCourse($courseId);
            $canvasCourseId = $canvasCourse->id;

            $courseSettingsRepository = new CourseSettingsRepository();
            $courseSettings = $courseSettingsRepository->getCourseSettings($canvasCourseId);
            $courseSettingsArray = $courseSettings != null ? $courseSettings->toArray() : [];
        } catch (ClientException $e) {
            if ($e->getResponse() && $e->getResponse()->getStatusCode() === 401) {
                return new ErrorResponse('Token is invalid', 401);
            } else if (str_contains($e->getMessage(), 'not found')) {
                return new ErrorResponse('Course not found', 404);
            }

            throw $e;
        } catch (\Throwable $th) {
            if (str_contains($th->getMessage(), 'not found'))
                return new ErrorResponse('Course not found', 404);

            throw $th;
        }

        try {
            $res = $this->canvasService->getCourseEnrollments($canvasCourseId, $perPage, false, $canvasToken, $page);
            $nextPage = $res['nextPage'] ?? null;
            $courseEnrollments = $res['data'] ?? [];
            $hideIndentedContentForLeaders = $courseSettingsArray != null && $courseSettingsArray["role_support"] == 1;

            $userEnrollmentUrls = [];
            $userIdMap = [];
            foreach ($courseEnrollments as $index => $enrollment) {
                $userId = $enrollment->user_id;
                $userEnrollmentUrls[$index] = "{$canvasDomain}/courses/{$courseId}/users?search_term={$userId}&include[]=enrollments&per_page=999";
                $userIdMap[$index] = $userId;
            }

            $userEnrollmentResponses = $this->makeConcurrentCanvasRequests($userEnrollmentUrls, $canvasToken);
            $moduleUrls = [];
            $moduleUserMap = [];
            $enrollmentData = [];

            foreach ($courseEnrollments as $index => $enrollment) {
                $userId = $enrollment->user_id;
                $userData = $enrollment->user;
                $userRole = strtolower($enrollment->role ?? '');
                $userIdInLetters = ucfirst($this->numberToLetterDigits("$userId"));

                if ($userRole != "student" &&$userRole != "studentenrollment" && $userRole != "skoleleder") {
                    continue;
                }

                $userEnrollmentResponse = $userEnrollmentResponses[$index];
                if (!method_exists($userEnrollmentResponse, 'failed') || $userEnrollmentResponse->failed()) {
                    throw new \Exception("Failed to fetch user enrollment for user ID {$userId} in course ID {$courseId}");
                }

                $decodedContentArray = (array)json_decode($userEnrollmentResponse->getBody()->getContents());
                if (empty($decodedContentArray)) continue;

                $decodedContent = (object)$decodedContentArray[0];
                $userEnrollments = $decodedContent->enrollments ?? [];
                $userEnrollmentRole = 'teacher';
                $leaderEnrollments = [];
                $teacherEnrollments = [];
                $hasUnsupportedRole = false;

                foreach ($userEnrollments as $userEnrollmentData) {
                    $enrollmentRole = strtolower($userEnrollmentData->role ?? '');

                    if ($enrollmentRole == "skoleleder") {
                        $userEnrollmentRole = "leader";
                        $leaderEnrollments[] = $userEnrollmentData;
                    } else if ($enrollmentRole == "student" || $enrollmentRole == "studentenrollment") {
                        $teacherEnrollments[] = $userEnrollmentData;
                    } else {
                        $hasUnsupportedRole = true;
                    }
                }

                if ($hasUnsupportedRole) continue;

                $enrollmentsToSort = $userEnrollmentRole == "leader" ? $leaderEnrollments : $teacherEnrollments;
                $finalEnrollment = $enrollmentsToSort[0];
                if (!empty($enrollmentsToSort)) {
                    usort($enrollmentsToSort, function($a, $b) {
                        $timeA = strtotime($a->updated_at ?? '1970-01-01');
                        $timeB = strtotime($b->updated_at ?? '1970-01-01');
                        return $timeB - $timeA;
                    });
                    $finalEnrollment = $enrollmentsToSort[0];
                }

                $enrollmentData[$index] = [
                    'userId' => $userId,
                    'userIdInLetters' => $userIdInLetters,
                    'userRole' => $userEnrollmentRole,
                    'userData' => $userData,
                    'enrollment' => $finalEnrollment
                ];

                $enrollmentRole = strtolower($finalEnrollment->role ?? '');
                $roleSupportedForProgress = $enrollmentRole == "studentenrollment" || $enrollmentRole == "skoleleder";

                if ($roleSupportedForProgress) {
                    $moduleUrls[$index] = "{$canvasDomain}/courses/{$courseId}/modules?include[]=items&student_id={$userId}&per_page=999";
                    $moduleUserMap[$index] = $userId;
                }
            }

            $enrollments = [];
            $moduleResponses = [];
            if (!empty($moduleUrls)) {
                $moduleResponses = $this->makeConcurrentCanvasRequests($moduleUrls, $canvasToken);
            }

            foreach ($enrollmentData as $index => $data) {
                $enrollment = $data['enrollment'];
                $userData = $data['userData'];
                $userId = $data['userId'];
                $userIdInLetters = $data['userIdInLetters'];
                $userRole = $data['userRole'];

                $requirementsCount = 0;
                $completedCount = 0;
                $completedDates = [];
                $modulesProgress = [];

                $enrollmentRole = strtolower($enrollment->role ?? '');
                $roleSupportedForProgress = $enrollmentRole == "studentenrollment" || $enrollmentRole == "skoleleder";

                if ($roleSupportedForProgress && isset($moduleResponses[$index])) {
                    $moduleResponse = $moduleResponses[$index];
                    if (!method_exists($moduleResponse, 'failed') || $moduleResponse->failed()) {
                        throw new \Exception("Failed to fetch modules for user ID {$userId} in course ID {$courseId}");
                    }

                    $modules = (array)json_decode($moduleResponse->getBody()->getContents()) ?? [];
                    foreach ($modules as $moduleData) {
                        $module = is_array($moduleData) ? (object)$moduleData : $moduleData;
                        $moduleId = $module->id ?? null;
                        $moduleItems = $module->items ?? [];
                        $moduleItemsProgress = [];

                        foreach ($moduleItems as $moduleItemData) {
                            $moduleItem = is_array($moduleItemData) ? (object)$moduleItemData : $moduleItemData;
                            $moduleType = strtolower($moduleItem->type ?? '');
                            $moduleItemRequirement = null;
                            $moduleItemIndent = $moduleItem->indent ?? 0;
                            $hideModuleItemRequirement = $hideIndentedContentForLeaders && $userRole == "teacher" && $moduleItemIndent != 0;
                            $requirementExists = isset($moduleItem->completion_requirement);

                            if ($requirementExists && !$hideModuleItemRequirement) {
                                $completionReq = is_array($moduleItem->completion_requirement)
                                    ? (object)$moduleItem->completion_requirement
                                    : $moduleItem->completion_requirement;

                                $moduleItemRequirementType = $completionReq->type ?? '';

                                if ($moduleItemRequirementType == "must_view") {
                                    $moduleItemRequirement = "view";
                                } else if ($moduleItemRequirementType == "must_mark_done") {
                                    $moduleItemRequirement = "mark";
                                }

                                $requirementsCount++;
                                if (($completionReq->completed ?? false) == true) $completedCount++;
                            }

                            $moduleItemsProgress[] = [
                                "id" => $moduleItem->id ?? null,
                                "indent" => $moduleItemIndent,
                                "requirement" => $moduleItemRequirement,
                                "completed" => $requirementExists && !$hideModuleItemRequirement
                                    ? ($moduleItem->completion_requirement->completed ?? false)
                                    : true,
                            ];
                        }

                        $completedDates[] = $module->completed_at ?? null;
                        $modulesProgress[] = [
                            "id" => $moduleId,
                            "completedAt" => $module->completed_at ?? null,
                            "items" => $moduleItemsProgress,
                        ];
                    }

                    $completedAt = null;
                    if ($requirementsCount <= $completedCount && $requirementsCount > 0) {
                        $completedDates = array_filter($completedDates);
                        if (!empty($completedDates)) {
                            usort($completedDates, function($a, $b) {
                                return strtotime($b) - strtotime($a);
                            });
                            $completedAt = $completedDates[0];
                        }
                    }

                    $enrollments[] = [
                        'id' => $enrollment->id ?? null,
                        'type' => $enrollment->type ?? null,
                        'role' => $userRole,
                        'requirementsCount' => $requirementsCount,
                        'completedCount' => $completedCount,
                        'completedAt' => $completedAt,
                        'createdAt' => $enrollment->created_at ?? null,
                        'updatedAt' => $enrollment->updated_at ?? null,
                        'user' => [
                            'id' => $userId,
                            'name' => $useRealData ? ($userData->name ?? '') : "John $userIdInLetters",
                            'email' => $useRealData ? ($userData->login_id ?? '') : "$userIdInLetters@test.kpas.no",
                            'createdAt' => $userData->created_at ?? null,
                        ],
                        'progressSupport' => $roleSupportedForProgress,
                        'modules' => $modulesProgress
                    ];
                }
            }

            return new SuccessResponse([
                'nextPage' => $nextPage,
                'roleSupport' => $hideIndentedContentForLeaders,
                'enrollments' => $enrollments
            ]);
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

    private function makeConcurrentCanvasRequests(array $urls, $authorizationHeader) {
        $headers = [
            'Authorization' => $authorizationHeader,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ];

        return Http::pool(function (Pool $pool) use ($urls, $headers) {
            foreach ($urls as $key => $url) {
                $pool->as($key)->withHeaders($headers)->timeout(30)->get($url);
            }
        });
    }
}
