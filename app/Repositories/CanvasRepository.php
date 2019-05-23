<?php

namespace App\Repositories;

use App\Dto\GroupDto;
use App\Dto\SectionDto;
use App\Exceptions\CanvasException;
use App\Services\CanvasService;

class CanvasRepository
{
    /**
     * @var CanvasService
     */
    protected $canvasService;

    public function __construct(CanvasService $canvasService)
    {
        $this->canvasService = $canvasService;
    }

    public function getUserByFeideId(string $feideId): \stdClass
    {
        $result = $this->canvasService->getUsersByFeideId($feideId);
        foreach ($result as $canvasUser) {
            if ($canvasUser->login_id === $feideId) {
                return $canvasUser;
            }
        }
        throw new CanvasException("User with id {$feideId} not found in " .json_encode($result));
    }

    public function addUserToGroup(int $userId, GroupDto $group, array $unenrollnmentIds)
    {
        $group = $this->getOrCreateGroup($group);

        $section = $this->getOrCreateSection($group);

        if (!empty($unenrollnmentIds)) {
            $this->canvasService->unenrollUserFrom($userId, $group->getCourseId(), $unenrollnmentIds);
        }

        $this->enrollStudent($userId, $section->getCourseId(), $section->getId());

        $this->canvasService->addUserToGroupId($userId, $group->getId());
    }

    protected function getOrCreateGroup(GroupDto $groupDto): GroupDto
    {
        if ($group = $this->findGroupId($groupDto)) {
            return $group;
        }

        return $this->canvasService->createGroup($groupDto);
    }

    protected function findGroupId(GroupDto $groupDto): ?GroupDto
    {
        $groups = $this->canvasService->getGroups($groupDto->getCategoryId());

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

    protected function getOrCreateSection(GroupDto $group): SectionDto
    {
        $sectionName = $group->getName() . ":" . $group->getDescription();

        $sectionDto = new SectionDto([
            'name' => $sectionName,
            'courseId' => $group->getCourseId(),
        ]);
        if ($section = $this->findSection($sectionDto)) {
            return $section;
        }

        return $this->canvasService->createSection($sectionDto);
    }

    protected function findSection(SectionDto $sectionDto): ?SectionDto
    {
        $sections = $this->canvasService->getSections($sectionDto->getCourseId());

        foreach ($sections as $section) {
            if ($section->name === $sectionDto->getName()) {
                $sectionDto->setId($section->id);
                return $sectionDto;
            }
        }

        return null;
    }

    protected function enrollStudent($userId, $courseId, $sectionId)
    {
        if ($roleId = $this->canvasService->getRoleIdFor("StudentEnrollment")) {
            return $this->canvasService->enroll($userId, $roleId, $courseId, $sectionId);
        }
        return null;

    }
}
