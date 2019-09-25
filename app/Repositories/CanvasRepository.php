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
        throw new CanvasException("User with id {$feideId} not found in " .json_encode($result, JSON_PRETTY_PRINT));
    }

    public function addUserToGroupInSection(int $userId, GroupDto $group, array $unenrollnmentIds = [])
    {
        $group = $this->getOrCreateGroup($group);

        $section = $this->getOrCreateSection($group);

        if (!empty($unenrollnmentIds)) {
            $this->canvasService->unenrollUserFrom($userId, $group->getCourseId(), $unenrollnmentIds);
        }

        $this->enrollStudentToSection($userId, $section->getCourseId(), $section->getId());

        $this->canvasService->addUserToGroupId($userId, $group->getId());
    }

    public function addUserToGroup(int $userId, GroupDto $group)
    {
        $group = $this->getOrCreateGroup($group);

        $this->canvasService->addUserToGroupId($userId, $group->getId());
    }

    public function enrollUserToCourse(int $userId, int $courseId, string $roleName)
    {
        $principalRoleName = config('canvas.principal_role');
        if ($roleName !== $principalRoleName) {
            $unenrollmentIds = [];
            $enrollments = collect($this->getUserEnrollments($userId));
            $enrollments->each(function ($enrollment) use ($courseId, $userId, $principalRoleName, &$unenrollmentIds) {
                if ($enrollment->role === $principalRoleName) {
                    $unenrollmentIds[] = $enrollment->id;
                }
            });
            $this->canvasService->unenrollUserFrom($userId, $courseId, $unenrollmentIds);
        }

        if ($roleId = $this->canvasService->getRoleIdFor($roleName)) {
            return $this->canvasService->enrollToCourse($userId, $roleId, $courseId);
        }


        throw new CanvasException(sprintf('Role with name %s not found!', $roleName));
    }

    public function getCourseById(int $courseId)
    {
        return $this->canvasService->getCourse($courseId);
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

    protected function enrollStudentToSection($userId, $courseId, $sectionId)
    {
        if ($roleId = $this->canvasService->getRoleIdFor(config('canvas.student_role'))) {
            return $this->canvasService->enrollToSection($userId, $roleId, $courseId, $sectionId);
        }
        return null;
    }

    public function getGroupCategories(int $courseIdId)
    {
        return $this->canvasService->getGroupCategories($courseIdId);
    }

    public function getUserEnrollments(int $userId)
    {
        return $this->canvasService->getEnrollments($userId);
    }

    public function getUserEnrollmentsByCourse(string $userLogin, int $courseId)
    {
        return $this->canvasService->getEnrollmentsByCourse($userLogin, $courseId);
    }

    public function getUserGroups(int $userId)
    {
        $groups = $this->canvasService->getUsersGroups($userId);

        return $groups;
    }

    public function removeUserFromGroups(int $userId, $groupsToRemove): void
    {
        foreach ($groupsToRemove as $category => $group) {
            logger("Remove from category " . $category . " group id " . $group["id"]);
            $this->canvasService->removeUserFromGroup($group["id"], $userId);
         };
    }

    public function removeUserGroups(int $userId, int $courseId): void
    {
        $groups = $this->canvasService->getUsersGroups($userId);
        $groupsToRemove = collect($groups)->filter(function ($group) use ($courseId) {
            return $group->course_id === $courseId;
        });

        $groupsToRemove->each(function ($group) use ($userId) {
           $this->canvasService->removeUserFromGroup($group->id, $userId);
        });
    }
}
