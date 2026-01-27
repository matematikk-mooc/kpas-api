<?php

namespace App\Repositories;

use App\Dto\GroupDto;
use App\Models\Group;
use App\Repositories\CanvasRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CanvasDbRepository extends CanvasRepository
{
    protected $searchablecolumns = [
        'id',
        'description',
        'category_id',
    ];

    protected function findGroupId(GroupDto $groupDto): ?GroupDto
    {
        if ($group = $this->findByArray($groupDto->toArray())) {
            $groupDto->setId($group->canvas_id);
            return $groupDto;
        }

        return null;
    }

    public function getOrCreateGroup(GroupDto $groupDto): GroupDto
    {
        logger("CanvasDbRepository::getOrCreateGroup category=" . $groupDto->getCategoryId());
        if ($group = $this->findGroupId($groupDto)) {
            if (env('APP_ENV') !== 'production') {
                $canvasGroups = $this->canvasService->getGroups($groupDto->getCategoryId());
                $matchingCanvasGroup = collect($canvasGroups)->first(function ($canvasGroup) use ($group) {
                    return $canvasGroup->description === $group->getDescription();
                });

                if ($matchingCanvasGroup && $matchingCanvasGroup->id != $group->getId()) {   
                    logger("CanvasDbRepository::getOrCreateGroup updating canvas_id from " . $group->getId() . " to " . $matchingCanvasGroup->id . " for description=" . $group->getDescription());                 
                    Group::where('id', $group->getId())->update(['canvas_id' => $matchingCanvasGroup->id]);
                    $group->setId($matchingCanvasGroup->id);
                }
            }

            return $group;
        }

        $group = $this->canvasService->createGroup($groupDto);

        Group::create($group->toArray());

        return $group;
    }

    public function getGroupsInGroupCategory(int $categoryId) 
    {
        logger("CanvasDbRepository::getGroupsInGroupCategory category=" . $categoryId);
        $groups = Group::where('category_id', $categoryId)->get();
        foreach ($groups as $group) {
            $canvasGroup = $this->canvasService->getGroup($group->canvas_id);
            $group["members_count"]  = $canvasGroup->members_count;
        }
        return $groups;
    }
    public function getNoOfGroups(int $categoryId) : int
    {
        return Group::where('category_id', $categoryId)->count();
    }

    /**
     * @param array $data
     * @return Model|Group|null
     */
    protected function findByArray(array $data): ?Group
    {
        $query = Group::query();

        foreach ($data as $key => $datum) {
            $snakeKey = Str::snake($key);
            if (in_array($snakeKey, $this->searchablecolumns)) {
                $query->where($snakeKey, $datum);
            }
        }

        return $query->first();
    }
}
