<?php

namespace App\Services;

use App\Dto\GroupDto;
use App\Dto\SectionDto;
use App\Exceptions\CanvasException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;

class CanvasService
{
    /**
     * @var Client
     */
    protected $guzzleClient;
    /**
     * @var string
     */
    protected $domain;
    /**
     * @var string
     */
    protected $accessKey;

    public function __construct(Client $guzzleClient)
    {
        $this->domain = config('canvas.domain');
        $this->accessKey = config('canvas.access_key');
        $this->guzzleClient = $guzzleClient;
    }

    public function getAccountInfoById(int $accountId){
        try {
            $url = "accounts/{$accountId}";
            return $this->request($url, 'GET');

        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Account with ID %s not found', $accountId));
            }
            throw $exception;
        }
    }

    public function getUsersByFeideId(string $feideId): array
    {
        $accountId = config('canvas.account_id');
        try {
            $url = "accounts/{$accountId}/users";
            $data = ['search_term' => $feideId];

            $response = $this->request($url, 'GET', $data);

            return is_array($response) ? $response : [];
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Account with ID %s not found', $accountId));
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
            logger(print_R($response, true));

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

            $response = $this->request($url, 'GET');
            logger(print_R($response, true));

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

            $response = $this->request($url, 'POST', [
                'name' => $groupDto->getName(),
                'description' => $groupDto->getDescription(),
            ]);

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
        logger("getGroup " . $groupId);
        try {
            $url = "groups/{$groupId}";

            return $this->request($url, 'GET');
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

            return $this->request($url, 'GET', [], [], true);
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

            $response = $this->request($url, 'POST', [
                'name' => $sectionDto->getName(),
            ]);

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
            foreach ($unenrollnmentIds as $unenrollnmentId) {
                $this->request(sprintf($url, $unenrollnmentId), 'DELETE', [
                    'task' => 'delete'
                ]);
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

            return $this->request($url, "POST", [
                'enrollment' => [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'enrollment_state' => "active",
                    'limit_privileges_to_course_section' => "true",
                    'self_enrolled' => "true",
                ],
            ]);
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

            return $this->request($url, "POST", [
                'enrollment' => [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'enrollment_state' => "active",
                    'limit_privileges_to_course_section' => "true",
                    'self_enrolled' => "true",
                ],
            ]);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getRoleIdFor(string $roleName): ?int
    {
        $accountId = config('canvas.account_id');
        try {
            $url = "accounts/{$accountId}/roles";
            $roles = $this->request($url);
            foreach ($roles as $role) {
                if ($role->role === $roleName) {
                    return $role->id;
                }
            }

            return null;
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Account with ID %s not found', $accountId));
            }
            throw $exception;
        }
    }

    public function addUserToGroupId(int $userId, int $groupId)
    {
        try {
            $url = "groups/{$groupId}/memberships";
            $this->request($url, 'POST', [
                'user_id' => $userId,
            ]);
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
            logger($url);
            return $this->request($url, 'GET', [], [], true);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getCourse(int $courseId)
    {
        try {
            $url = "courses/{$courseId}?include[]=public_description";
            return $this->request($url);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getAllCourses()
    {
        $accountId = config('canvas.account_id');
        $url = "accounts/{$accountId}/courses";
        return $this->request($url, 'GET', [], [], true);
    }

    public function getAllAccountCourses($accountId)
    {
        $url = "accounts/{$accountId}/courses?include[]=course_image&per_page=100";
        return $this->request($url);
    }

    public function getAllPublishedCourses()
    {
        $url = "search/all_courses?open_enrollment_only=true&per_page=999";
        return $this->request($url, 'GET', [], [], true, true);
    }

    public function getCourseModules($courseId)
    {
        $accountId = config('canvas.account_id');
        $url = "courses/{$courseId}/modules";
        return $this->request($url, 'GET', [], [], true);
    }

    public function getCourseModuleItems($courseId, $moduleId)
    {
        $url = "courses/{$courseId}/modules/{$moduleId}/items";
        return $this->request($url, 'GET', [], [], true);
    }

    public function getCourseDiscussionTopics($courseId)
    {
        $url = "courses/{$courseId}/discussion_topics";
        return $this->request($url, 'GET', [], [], true);
    }

    public function getAssignmentsForCourse(int $courseId)
    {
        $url = "courses/{$courseId}/assignments";
        return $this->request($url, 'GET', [], [], true);
    }

    public function getCourseEnrollments(int $courseId)
    {
        $url = "courses/{$courseId}/enrollments";
        return $this->request($url, 'GET', ["per_page" => 100], [], true);
    }

    public function getEnrollments(int $userId)
    {
        try {
            $url = "users/{$userId}/enrollments";
            return $this->request($url, 'GET', [], [], true);
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
            $url = "courses/{$courseId}/users?search_term={$userId}&include[]=enrollments";
            logger($url);
            $result = $this->request($url);

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
            logger("CanvasService.getEnrollmentsByCourse: ".$exception->getMessage());
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getCourseUser(int $courseId, int $userId)
    {
        try {
            $url = "courses/{$courseId}/users?include[]=enrollments&user_ids[]={$userId}";
            return $this->request($url, 'GET', [], [], true);
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
            $isStudent = $roleExists && $enrollment->role === 'StudentEnrollment';
            $isPreviewStudent = $roleExists && $enrollment->role === 'StudentViewEnrollment';
            if ($isStudent || $isPreviewStudent) return true;
        }

        return false;
    }

    public function getModulesForCourse(int $courseId, int $studentId)
    {
        try {
            $modulesHref = "courses/{$courseId}/modules";
            $modules = $this->request($modulesHref, 'GET', [], [], true);
            $courseUser = $this->getCourseUser($courseId, $studentId);
            $isStudent = $this->hasStudentEnrollment($courseUser);

            foreach($modules as $module) {
                if($module->published) {
                    $moduleId = $module->id;
                    $itemsHref = $isStudent ? "courses/{$courseId}/modules/{$moduleId}/items?student_id={$studentId}" : "courses/{$courseId}/modules/{$moduleId}/items";
                    logger($itemsHref);
                    $items = $this->request($itemsHref, 'GET', [], [], true);
                    $module->items = $items;
                }
            }

            return $modules;
        } catch (ClientException $exception) {
            logger("CanvasService.getModulesForCourse: ".$exception->getMessage());
            throw $exception;
        }
    }

    public function getUsersGroups(int $userId)
    {
        try {
            $url = "users/self/groups?as_user_id={$userId}";
            return $this->request($url, 'GET', [
                'as_user_id' => $userId
            ]);
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
            return $this->request($url, 'GET', [], [], true);
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
        return $this->request($url, 'GET');
    }

    public function removeUserFromGroup(int $groupId, int $userId)
    {
        return $this->request("groups/{$groupId}/users/{$userId}", 'DELETE');
    }

    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false, bool $skip_auth = false)
    {
        $fullUrl = "{$this->domain}/{$url}";

        if ($skip_auth != true) {
            $headers = array_merge([
                'Authorization' => 'Bearer ' . $this->accessKey,
            ], $headers);
        }

        $isFinished = false;

        try {
            $content = [];
            while (!$isFinished) {
                $response = $this->guzzleClient->request($method, $fullUrl, [
                    'form_params' => $data,
                    'headers' => $headers,
                    'verify' => false,
                ]);

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
                if (!$paginable || !preg_match('/<([^<]+)>; rel="next"/', $link[0], $matches)) {
                    $isFinished = true;
                    continue;
                }
                $fullUrl = $matches[1];
            }
            logger("CanvasService: returning content");
            return $content;
        } catch (ClientException $exception) {
            logger("CanvasService.request exception:");
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
        return $this->request($url, 'GET');
    }

    public function getCoursePages(int $courseId)
    {
        $url = "courses/{$courseId}/pages?per_page=100";
        return $this->request($url, 'GET');
    }

    public function getCourseFrontPage(int $courseId)
    {
        $url = "courses/{$courseId}/front_page";
        return $this->request($url, 'GET');
    }

    public function getCoursePageContent(int $courseId, int $pageId)
    {
        $url = "courses/{$courseId}/pages/{$pageId}";
        return $this->request($url, 'GET');
    }

    public function getLinksValidationForCourse(int $courseId)
    {
        $url = "courses/{$courseId}/link_validation";
        return $this->request($url, 'GET');
    }
}
