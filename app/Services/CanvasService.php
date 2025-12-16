<?php

namespace App\Services;

use App\Dto\GroupDto;
use App\Dto\SectionDto;
use App\Utils\SentryTrace;
use App\Exceptions\CanvasException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;

class CanvasService
{
    /**
     * @var string
     */
    protected $domain;
    /**
     * @var string
     */
    protected $accessKey;
    /**
     * @var string
     */
    protected $accountId;

    public function __construct()
    {
        $this->domain = config('canvas.domain');
        $this->accessKey = config('canvas.access_key');
        $this->accountId = config('canvas.account_id');
    }

    public function getAccountInfoById(int $accountId)
    {
        try {
            $url = "accounts/{$accountId}";

            return $this->request($url);

        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Account with ID %s not found', $accountId));
            }
            throw $exception;
        }
    }

    public function getUsersByFeideId(string $feideId): array
    {
        try {
            $url = "accounts/{$this->accountId}/users";
            $data = ['search_term' => $feideId];

            $response = $this->request($url, 'GET', $data);

            return is_array($response) ? $response : [];
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Account with ID %s not found', $this->accountId));
            }

            throw $exception;
        }
    }

    public function getTotalStudents(string $courseId): array
    {
        try {
            $url = "courses/{$courseId}";
            $data = ['include[]' => "total_students"];

            $response = $this->request($url, 'GET', $data);

            return ['antallBrukere' => $response->total_students];
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Statistics for course with ID %s not found', $courseId));
            }

            throw $exception;
        }
    }

    public function getTotalStudentsByGroup(string $groupId): array
    {
        try {
            $url = "groups/{$groupId}";

            $response = $this->request($url);

            return ['antallBrukere' => $response->members_count];
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Statistics for group with ID %s not found', $groupId));
            }

            throw $exception;
        }
    }

    public function createGroup(GroupDto $groupDto): GroupDto
    {
        try {
            $url = "group_categories/{$groupDto->getCategoryId()}/groups";
            $data = ['name' => $groupDto->getName(),
                     'description' => $groupDto->getDescription()];

            $response = $this->request($url, 'POST', $data);

            $groupDto->setId($response->id);

            return $groupDto;
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf(
                    'Group category with ID %s not found',
                    $groupDto->getCategoryId()
                ));
            }
            throw $exception;
        }
    }
    public function getGroup(int $groupId)
    {
        try {
            $url = "groups/{$groupId}";

            return $this->request($url);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Group with ID %s not found', $groupId));
            }
            throw $exception;
        }
    }

    public function getGroups(int $categoryId): array
    {
        try {
            $url = "group_categories/{$categoryId}/groups";
            $data = ['page' => 1, 'per_page' => 100];

            return $this->request($url, 'GET', $data, [], true);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Group category with ID %s not found', $categoryId));
            }
            throw $exception;
        }
    }

    public function createSection(SectionDto $sectionDto): SectionDto
    {
        try {
            $url = "courses/{$sectionDto->getCourseId()}/sections";
            $data = ['name' => $sectionDto->getName()];

            $response = $this->request($url, 'POST', $data);

            $sectionDto->setId($response->id);

            return $sectionDto;
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $sectionDto->getCourseId()));
            }
            throw $exception;
        }
    }

    public function getSections(int $courseId): array
    {
        try {
            $url = "courses/{$courseId}/sections";

            return $this->request($url);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function unenrollUserFrom(int $userId, ?int $courseId, array $unenrollnmentIds)
    {
        try {
            $url = "courses/{$courseId}/enrollments/%s";
            $data = ['task' => 'delete'];

            foreach ($unenrollnmentIds as $unenrollnmentId) {
                $this->request(sprintf($url, $unenrollnmentId), 'DELETE', $data);
            }
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function enrollToSection($userId, $roleId, $courseId, $sectionId)
    {
        try {
            $url = "sections/{$sectionId}/enrollments";
            $data = ['enrollment' => [
                     'user_id' => $userId,
                     'role_id' => $roleId,
                     'enrollment_state' => "active",
                     'limit_privileges_to_course_section' => "true",
                     'self_enrolled' => "true",
                ],
            ];

            return $this->request($url, "POST", $data);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Section with ID %s not found', $sectionId));
            }
            throw $exception;
        }
    }

    public function enrollToCourse($userId, $roleId, $courseId)
    {
        try {
            $url = "courses/{$courseId}/enrollments";
            $data = [
                'enrollment' => [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'enrollment_state' => "active",
                    'limit_privileges_to_course_section' => "true",
                    'self_enrolled' => "true",
                ],
            ];

            return $this->request($url, "POST", $data);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getRoleIdFor(string $roleName): ?int
    {
        try {
            $url = "accounts/{$this->accountId}/roles";

            $roles = $this->request($url);
            foreach ($roles as $role) {
                if ($role->role === $roleName) {
                    return $role->id;
                }
            }

            return null;
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Account with ID %s not found', $this->accountId));
            }
            throw $exception;
        }
    }

    public function addUserToGroupId(int $userId, int $groupId)
    {
        try {
            $url = "groups/{$groupId}/memberships";
            $data = ['user_id' => $userId];

            $this->request($url, 'POST', $data);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Group with ID %s not found', $groupId));
            }
            throw $exception;
        }
    }

    public function getGroupCategories(int $courseId)
    {
        try {
            $url = "courses/{$courseId}/group_categories";
            $data = ['page' => 1, 'per_page' => 100];

            return $this->request($url, 'GET', $data, [], true);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getGroupMemberships(int $groupId, int $perPage = 100, bool $paginable = true, string $authorizationHeader = null, string $page = null)
    {
        try {
            $url = "groups/{$groupId}/memberships";
            $data = ['per_page' => $perPage];
            $headers = [];

            $usePage = !empty($page);
            if ($usePage) $data['page'] = $page;

            $useCustomToken = !empty($authorizationHeader);
            if ($useCustomToken) $headers['Authorization'] = $authorizationHeader;

            return $this->request($url, 'GET', $data, $headers, $paginable, $useCustomToken, !$paginable);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course group category with ID %s not found', $groupId));
            }
            throw $exception;
        }
    }

    public function getCourse(int $courseId)
    {
        try {
            $url = "courses/{$courseId}";
            $data = ['include[]' => 'public_description'];

            return $this->request($url, 'GET', $data);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getAllCourses()
    {
        $url = "accounts/{$this->accountId}/courses";
        $data = ['page' => 1, 'per_page' => 100];

        return $this->request($url, 'GET', $data, [], true);
    }

    public function getAllAccountCourses($accountId)
    {
        $url = "accounts/{$accountId}/courses";
        $data = ['public' => true, 'published' => true, 'include[]' => 'course_image', 'page' => 1, 'per_page' => 100];

        return $this->request($url, 'GET', $data, [], true);
    }

    public function getAllPublishedCourses()
    {
        $url = "search/all_courses";
        $data = ['open_enrollment_only' => true, 'page' => 1, 'per_page' => 999];

        return $this->request($url, 'GET', $data, [], true, true);
    }

    public function getCourseModules($courseId, $includeItems = false)
    {
        $url = "courses/{$courseId}/modules";
        $data = ['page' => 1, 'per_page' => 100];

        if ($includeItems) $data['include[]'] = "items";

        return $this->request($url, 'GET', $data, [], true);
    }

    public function getAnnouncements(int $courseId)
    {
        $url = "courses/$courseId/discussion_topics";
        $data = ['page' => 1, 'per_page' => 100,
                 'only_announcements' => true, 'no_avatar_fallback' => '1'];
        return $this->request($url, 'GET', $data, [], true);
    }

    public function getCourseModuleItems($courseId, $moduleId)
    {
        $url = "courses/{$courseId}/modules/{$moduleId}/items";
        $data = ['page' => 1, 'per_page' => 100];

        return $this->request($url, 'GET', $data, [], true);
    }

    public function getCourseDiscussionTopics($courseId)
    {
        $url = "courses/{$courseId}/discussion_topics";
        $data = ['page' => 1, 'per_page' => 100];

        return $this->request($url, 'GET', $data, [], true);
    }

    public function getAssignmentsForCourse(int $courseId)
    {
        $url = "courses/{$courseId}/assignments";
        $data = ['page' => 1, 'per_page' => 100];

        return $this->request($url, 'GET', $data, [], true);
    }

    public function getCourseEnrollments(int $courseId, int $per_page = 100, bool $paginable = true, string $authorizationHeader = null, string $page = null)
    {
        $url = "courses/{$courseId}/enrollments";
        $data = ['per_page' => $per_page];
        $headers = [];

        $usePage = !empty($page);
        if ($usePage) $data['page'] = $page;

        $useCustomToken = !empty($authorizationHeader);
        if ($useCustomToken) $headers['Authorization'] = $authorizationHeader;

        return $this->request($url, 'GET', $data, $headers, $paginable, $useCustomToken, !$paginable);
    }

    public function getEnrollments(int $userId)
    {
        try {
            $url = "users/{$userId}/enrollments";
            $data = ['page' => 1, 'per_page' => 100];

            return $this->request($url, 'GET', $data, [], true);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('User with ID %s not found', $userId));
            }
            throw $exception;
        }
    }

    public function getEnrollmentsByCourse(string $userId, int $courseId)
    {
        try {
            $url = "courses/{$courseId}/users";
            $data = ['search_term' => $userId, 'include[]' => 'enrollments'];
            $result = $this->request($url, 'GET', $data);

            if(empty($result)) {
                return [];
            }

            foreach($result as $item) {
                if($item->id == $userId) {
                    return $item->enrollments;
                }
            }
            return [];
        } catch (ClientException $exception) {
            logger("CanvasService::getEnrollmentsByCourse error=" . $exception->getMessage());
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getCourseUser(int $courseId, int $userId)
    {
        try {
            $url = "courses/{$courseId}/users";
            $data = ['include[]' => 'enrollments','user_ids[]' => $userId,
                     'page' => 1, 'per_page' => 100];

            return $this->request($url, 'GET', $data, [], true);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    private function hasStudentEnrollment(array $courseUser): bool
    {
        if (empty($courseUser)) return false;

        $user = $courseUser[0];
        if (!isset($user->enrollments) || !is_array($user->enrollments)) return false;

        foreach ($user->enrollments as $enrollment) {
            $roleExists = isset($enrollment->role);

            if ($roleExists) {
                $role = $enrollment->role;
                $isStudent = $role === 'StudentEnrollment';
                $isPreviewStudent = $role === 'StudentViewEnrollment';

                $log_user_id = isset($user->id) ? $user->id : "unknown";
                $log_course_id = isset($user->course_id) ? $user->course_id : "unknown";
                logger("CanvasService::hasStudentEnrollment user_id=" . $log_user_id . " course_id=" . $log_course_id . " role=" . $role);

                if ($isStudent || $isPreviewStudent) return true;
            }

        }

        return false;
    }

    public function getModulesWithProgress(int $courseId, int $userId) {
        try {
            $modulesHref = "courses/{$courseId}/modules";
            $data = ['page' => 1, 'per_page' => 100,
                     'include[]' => 'items', 'student_id' => $userId];

            return $this->request($modulesHref, 'GET', $data, [], true);
        } catch (ClientException $exception) {
            logger("CanvasService::getModulesWithProgress error=" . $exception->getMessage());
            throw $exception;
        }
    }

    public function getModulesForCourse(int $courseId, int $studentId)
    {
        try {
            $modulesHref = "courses/{$courseId}/modules";
            $data = ['page' => 1, 'per_page' => 100];

            $modules = $this->request($modulesHref, 'GET', $data, [], true);
            $courseUser = $this->getCourseUser($courseId, $studentId);
            $isStudent = $this->hasStudentEnrollment($courseUser);

            logger("CanvasService::getModulesForCourse course_id=" . $courseId . " student_id=" . $studentId . " is_student=" . ($isStudent ? "true" : "false"));

            foreach($modules as $module) {
                if($module->published) {
                    $moduleId = $module->id;
                    $itemsHref = $isStudent ? "courses/{$courseId}/modules/{$moduleId}/items?student_id={$studentId}" : "courses/{$courseId}/modules/{$moduleId}/items";
                    
                    $items = $this->request($itemsHref, 'GET', $data, [], true);
                    $module->items = $items;
                }
            }

            return $modules;
        } catch (ClientException $exception) {
            logger("CanvasService::getModulesForCourse error=" . $exception->getMessage());
            throw $exception;
        }
    }

    public function getUsersGroups(int $userId)
    {
        try {
            $url = "users/self/groups";
            $data = ['as_user_id' => $userId];

            return $this->request($url, 'GET', $data);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 401) {
                throw new CanvasException(sprintf('User with ID %s not found', $userId));
            }
            throw $exception;
        }
    }

    public function getUsersInGroup(int $groupId)
    {
        try {
            $url = "groups/{$groupId}/users";
            $data = ['page' => 1, 'per_page' => 999];

            return $this->request($url, 'GET', $data, [], true);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 401 || $exception->getCode() === 404) {
                throw new CanvasException(sprintf('Group with ID %s not found', $groupId));
            }
            throw $exception;
        }
    }

    public function mergeUsers(int $fromUserId, int $toUserId)
    {
        $url = "users/$fromUserId/merge_into/$toUserId";

        return $this->request($url, 'PUT');
    }

    public function deleteUser(int $userId)
    {
        $accountId = 1;
        $url = "accounts/{$accountId}/users/{$userId}";

        return $this->request($url, 'DELETE');
    }

    public function getUser(int $userId)
    {
        $url = "users/{$userId}";

        return $this->request($url);
    }

    public function removeUserFromGroup(int $groupId, int $userId)
    {
        return $this->request("groups/{$groupId}/users/{$userId}", 'DELETE');
    }

    protected function request(
        string $url,
        string $method = 'GET',
        array $data = [],
        array $headers = [],
        bool $paginable = false,
        bool $skip_auth = false,
        bool $return_next = false
    ) {
        $fullUrl = "{$this->domain}/{$url}";
        $isFinished = false;
        $content = [];
        $nextPage = null;

        // Build headers
        if (!$skip_auth) {
            $headers = array_merge([
                'Authorization' => 'Bearer ' . $this->accessKey,
                'Accept'        => 'application/json',
            ], $headers);
        }

        // Start options with headers + verify
        $options = [
            'headers' => $headers,
            'verify'  => false,
        ];

        try {
            while (!$isFinished) {
                // Add data depending on method inside loop for pagination support
                if ($method === 'GET') {
                    $options['query'] = $data;
                } else {
                    $options['form_params'] = $data;
                }

                $response = SentryTrace::guzzleRequest($method, $fullUrl, $options);

                $decodedContent = json_decode($response->getBody()->getContents());
                $content = is_array($decodedContent) ? array_merge($content, $decodedContent) : $decodedContent;

                if (config('canvas.debug')) {
                    info(json_encode([
                        'url' => $fullUrl,
                        'method' => $method,
                        'data' => $data,
                        'headers' => $headers,
                        'response' => $content
                    ], JSON_PRETTY_PRINT));
                }

                $link = $response->getHeader('Link');
                $linkExists = isset($link[0]);

                if ($linkExists) {
                    if (preg_match('/<([^>]+)>; rel="next"/', $link[0], $matchesNext)) {
                        $nextLink = $matchesNext[1];

                        if (preg_match('/page=([^&]+)/', $nextLink, $pageMatches)) {
                            $nextPage = str_replace("page=", "", $pageMatches);
                        }
                    }
                }

                /*
                // Debugging pagination issues
                error_log(print_r([
                    'url' => $fullUrl,
                    'method' => $method,
                    'paginable' => $paginable,
                    'linkExists' => $linkExists,
                    'nextPage' => $nextPage[0] ?? false,
                    'data' => $data
                ], true));
                */

                if (!$paginable || $linkExists && !preg_match('/<([^<]+)>; rel="next"/', $link[0], $matches)) {
                    $isFinished = true;
                    continue;
                } else if ($paginable && $linkExists && $nextPage[0] ?? false) {
                    $data['page'] = $nextPage[0]; // If next page exists in link header from Canvas, update page field for next request
                }
            }

            if ($return_next) return ["nextPage" => $nextPage[0] ?? null, "data" => $content];
            return $content;
        } catch (ClientException $exception) {
            logger("CanvasService::request error=" . $exception->getMessage());
            if (config('canvas.debug')) {
                info(json_encode([
                    'url' => $fullUrl,
                    'method' => $method,
                    'data' => $data,
                    'headers' => $headers,
                    'response' => json_decode($exception->getResponse()->getBody()->getContents())
                ], JSON_PRETTY_PRINT));
            }

            throw $exception;
        }
    }

    public function accountIsChildOf(int $udirCanvasParentAccountId, int $subAccountId)
    {
        if ($udirCanvasParentAccountId == $subAccountId){
            return true;
        }

        $accountInfo = $this->getAccountInfoById($subAccountId);
        $parentAccountId = $accountInfo->parent_account_id;
        if (!(isset($parentAccountId))){
            return false;
        }else{
            return $this->accountIsChildOf($udirCanvasParentAccountId, $parentAccountId);
        }
    }

    public function getCourseData(int $courseId)
    {
        $url = "courses/{$courseId}";

        return $this->request($url);
    }

    public function getCoursePages(int $courseId)
    {
        $url = "courses/{$courseId}/pages";
        $data = ['per_page' => 100];

        return $this->request($url, 'GET', $data);
    }

    public function getCourseFrontPage(int $courseId)
    {
        $url = "courses/{$courseId}/front_page";

        return $this->request($url);
    }

    public function getCoursePageContent(int $courseId, int $pageId)
    {
        $url = "courses/{$courseId}/pages/{$pageId}";

        return $this->request($url);
    }

    public function getCoursePageContentByPath(int $courseId, string $pagePath)
    {
        $url = "courses/{$courseId}/pages/{$pagePath}";

        return $this->request($url);
    }

    public function getLinksValidationForCourse(int $courseId)
    {
        $url = "courses/{$courseId}/link_validation";

        return $this->request($url);
    }

    public function postLinksValidationForCourse(int $courseId)
    {
        $url = "courses/{$courseId}/link_validation";

        return $this->request($url, 'POST');
    }
}
