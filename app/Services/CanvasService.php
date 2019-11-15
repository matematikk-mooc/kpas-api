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
            $url = "accounts/${accountId}/roles";
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
            $url = "courses/{$courseId}";
            return $this->request($url);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Course with ID %s not found', $courseId));
            }
            throw $exception;
        }
    }

    public function getEnrollments(int $userId)
    {
        try {
            $url = "users/{$userId}/enrollments";
            return $this->request($url);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('User with ID %s not found', $userId));
            }
            throw $exception;
        }
    }

    public function getEnrollmentsByCourse(string $userLogin, int $courseId)
    {
        try {
            $url = "courses/{$courseId}/users?search_term={$userLogin}&include[]=enrollments";
            $result = $this->request($url);

            if(empty($result)) {
                throw new CanvasException('No results found');
            }

            foreach($result as $item) {
                if($item->login_id == $userLogin) {
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

    public function removeUserFromGroup(int $groupId, int $userId)
    {
        return $this->request("groups/{$groupId}/users/{$userId}", 'DELETE');
    }

    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [], bool $paginable = false)
    {
        $fullUrl = "{$this->domain}/{$url}";

        $headers = array_merge([
            'Authorization' => 'Bearer ' . $this->accessKey,
        ], $headers);

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
}
