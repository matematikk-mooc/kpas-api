<?php

namespace App\Dto;

class GroupDto extends AbstractDto
{
    /** @var integer|null */
    protected $groupCategoryId;

    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $description;

    /** @var int|null */
    protected $id;

    public function getGroupCategoryId(): ?int
    {
        return $this->groupCategoryId;
    }

    public function setGroupCategoryId(?int $groupCategoryId): void
    {
        $this->groupCategoryId = $groupCategoryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
