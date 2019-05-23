<?php

namespace App\Services;

use App\Dto\GroupDto;
use App\Dto\SectionDto;
use App\Exceptions\CanvasException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

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

    /** Actual service */


    public function getUsersByFeideId(int $feideId): array
    {
        $accountId = config('canvas.account_id');
        try {
            $url = "accounts/{$accountId}/users";
            $data = ['search' => $feideId];

            return $this->request($url, 'GET', $data);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Account with ID %s not found', $accountId));
            }
        }
    }

    public function createGroup(GroupDto $groupDto): GroupDto
    {
        try {
            $url = "group_categories/{$groupDto->getGroupCategoryId()}/groups";

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
                    $groupDto->getGroupCategoryId()
                ));
            }
        }
    }

    public function getGroups(int $categoryId): array
    {
        try {
            $url = "group_categories/{$categoryId}/groups";

            return $this->request($url, 'GET', ['per_page' => 999]);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Group category with ID %s not found', $categoryId));
            }
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
        }
    }

    public function enroll($userId, $roleId, $courseId, $sectionId)
    {
        try {
            $url = "sections/{$sectionId}/enrollments";

            return $this->request($url, "POST", [
                'enrollment' => [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                    'enrollment_state' => "active",
                    'limit_privileges_to_course_section' => "true",
                    'self_emrolled' => "true",
                ],
            ]);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                throw new CanvasException(sprintf('Section with ID %s not found', $sectionId));
            }
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
            throw new CanvasException(sprintf('Account with ID %s not found', $accountId));
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
        }
    }

    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [])
    {
        $fullUrl = "{$this->domain}/{$url}";

        $headers = array_merge([
            'Authorization' => 'Bearer ' . $this->accessKey,
        ], $headers);


        $response = $this->guzzleClient->request($method, $fullUrl, [
            'form_params' => $data,
            'headers' => $headers,
            'verify' => false,
        ]);

        $content = json_decode($response->getBody()->getContents());

        if (config('canvas.debug')) {
            info(json_encode([
                'url' => $url,
                'method' => $method,
                'data' => $data,
                'headers' => $headers,
                'response' => $content
            ], JSON_PRETTY_PRINT));
        }

        return $content;
    }

}
