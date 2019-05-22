<?php

namespace App\Services;

use App\Dto\GroupDto;
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

    public function addUserToGroup(int $id, GroupDto $group, array $unenrollnmentIds)
    {

    }

    protected function getOrCreateGroup(GroupDto $groupDto): int
    {
        if ($groupId = $this->findGroupId($groupDto)) {
            return $groupId;
        }

        return $this->createGroup($groupDto);
    }

    protected function findGroupId(GroupDto $groupDto): ?int
    {
        $groups = $this->getGroups($groupDto->getGroupCategoryId());

        foreach ($groups as $group) {
            if (
                $group->name === $groupDto->getName()
                && $group->description === $groupDto->getDescription()
            ) {
                return $group->id;
            }
        }

        return null;
    }

    protected function createGroup(GroupDto $groupDto): int
    {
        $url = "group_categories/{$groupDto->getGroupCategoryId()}/groups";

        $response = $this->request($url, 'POST', [
            'name' => $groupDto->getName(),
            'description' => $groupDto->getDescription(),
        ]);

        return $response->id;
    }

    public function getGroups(int $categoryId): array
    {
        $url = "group_categories/{$categoryId}/groups";

        return $this->request($url, 'GET', ['per_page' => 999]);
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
