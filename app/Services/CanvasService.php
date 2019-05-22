<?php

namespace App\Services;

use App\Dto\GroupDto;
use App\Dto\SectionDto;
use GuzzleHttp\Client;
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

    public function getUserByFeideId(int $feideId): \stdClass
    {
        $accountId = config('canvas.account_id');
        $url = "/accounts/{$accountId}/users";
        $data = ['search' => $feideId];


        $result = $this->request($url, 'GET', $data);
        foreach ($result as $canvasUser) {
            if ($canvasUser->id === $feideId) {
                return $canvasUser;
            }
        }
        throw new \Exception("User with id {$feideId} not found in " .json_encode($result));
    }

    public function addUserToGroup(int $userId, GroupDto $group, array $unenrollnmentIds)
    {
        $group = $this->getOrCreateGroup($group);

        $section = $this->getOrCreateSection($group);

        if (!empty($unenrollnmentIds)) {
            $this->unenrollUserFrom($userId, $group->getCourseId(), $unenrollnmentIds);
        }
    }

    protected function getOrCreateGroup(GroupDto $groupDto): GroupDto
    {
        if ($group = $this->findGroupId($groupDto)) {
            return $group;
        }

        return $this->createGroup($groupDto);
    }

    protected function findGroupId(GroupDto $groupDto): ?GroupDto
    {
        $groups = $this->getGroups($groupDto->getGroupCategoryId());

        foreach ($groups as $group) {
            if (
                $group->name === $groupDto->getName()
                && $group->description === $groupDto->getDescription()
            ) {
                $groupDto->setId($group->id);
                return $groupDto;
            }
        }

        return null;
    }

    protected function createGroup(GroupDto $groupDto): GroupDto
    {
        $url = "group_categories/{$groupDto->getGroupCategoryId()}/groups";

        $response = $this->request($url, 'POST', [
            'name' => $groupDto->getName(),
            'description' => $groupDto->getDescription(),
        ]);

        $groupDto->setId($response->id);
        return $groupDto;
    }

    public function getGroups(int $categoryId): array
    {
        $url = "group_categories/{$categoryId}/groups";

        return $this->request($url, 'GET', ['per_page' => 999]);
    }


    protected function getOrCreateSection(GroupDto $group): SectionDto
    {
        $sectionName = $group->getName() . ":" . $group->getDescription();

        $sectionDto = new SectionDto([
            'name' => $sectionName,
            'courseId' => $group->getCourseId(),
        ]);
        if ($section = $this->findSection($sectionDto)) {
            $sectionDto->setId($section->id);
            return $sectionDto;
        }

        return $this->createSection($sectionDto);
    }

    protected function createSection(SectionDto $sectionDto): SectionDto
    {
        $url = "courses/{$sectionDto->getCourseId()}/sections";

        $response = $this->request($url, 'POST', [
            'name' => $sectionDto->getName(),
        ]);

        $sectionDto->setId($response->id);
        return $sectionDto;
    }

    protected function findSection(SectionDto $sectionDto): ?SectionDto
    {
        $sections = $this->getSections($sectionDto->getCourseId());

        foreach ($sections as $section) {
            if ($section->name === $sectionDto->getName()) {
                $sectionDto->setId($section->id);
                return $sectionDto;
            }
        }

        return null;
    }

    protected function getSections(int $courseId): array
    {
        $url = "courses/{$courseId}/sections";

        return $this->request($url);
    }

    protected function unenrollUserFrom(int $userId, ?int $courseId, array $unenrollnmentIds)
    {
        $url = "courses/{$courseId}/enrollment/%s";
        foreach ($unenrollnmentIds as $unenrollnmentId) {
            $this->request(sprintf($url, $unenrollnmentId), 'DELETE', [
                'task' => 'delete'
            ]);
        }
    }

    protected function request(string $url, string $method = 'GET', array $data = [], array $headers = [])
    {
        $fullUrl = "{$this->domain}/{$url}";

        $headers = array_merge([
            'Authorization' => 'Bearer ' . $this->accessKey,
        ], $headers);


        try {
            $response = $this->guzzleClient->request($method, $fullUrl, [
                'body' => $data,
                'headers' => $headers,
            ]);
        } catch (GuzzleException $exception) {
            throw new \Exception("Canvas: " . $exception->getMessage());
        }

        return json_encode($response);
    }

}
