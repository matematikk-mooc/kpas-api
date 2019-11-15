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
        'name',
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
        if ($group = $this->findGroupId($groupDto)) {
            return $group;
        }

        $group = $this->canvasService->createGroup($groupDto);

        Group::create($group->toArray());

        return $group;
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
