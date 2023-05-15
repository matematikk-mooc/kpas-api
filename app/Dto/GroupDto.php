<?php

namespace App\Dto;

use Illuminate\Support\Arr;

class GroupDto extends AbstractDto
{
    /** @var integer|null */
    protected $categoryId;

    /** @var string|null */
    protected $name;

    /** @var string|null */
    protected $description;

    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $membership;

    /** @var int|null */
    protected $courseId;

    /** @var int|null */
    protected $canvasId;

    /** @var int|null */
    protected $countyId;

    /** @var int|null */
    protected $communityId;

    /** @var string|null */
    protected $orgNr;

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(?int $categoryId): void
    {
        $this->categoryId = $categoryId;
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

    public function getMembership(): ?string
    {
        return $this->membership;
    }

    public function setMembership(?string $membership): void
    {
        $this->membership = $membership;
    }

    public function getCourseId(): ?int
    {
        return $this->courseId;
    }

    public function setCourseId(?int $courseId): void
    {
        $this->courseId = $courseId;
    }

    public function getCountyId(): ?int
    {
        return $this->countyId;
    }

    public function setCountyId(?int $countyId): void
    {
        $this->countyId = $countyId;
    }

    public function getCommunityId(): ?int
    {
        return $this->communityId;
    }

    public function setCommunityId(?int $communityId): void
    {
        $this->communityId = $communityId;
    }

    public function getOrgNr(): ?string
    {
        return $this->orgNr;
    }

    public function setOrgNr(?string $orgNr): void
    {
        $this->orgNr = $orgNr;
    }

    public function getCanvasId(): ?int
    {
        return $this->canvasId;
    }

    public function setCanvasId(?int $canvasId): void
    {
        $this->canvasId = $canvasId;
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        $array['canvas_id'] = $array['id'];
        Arr::forget($array, 'id');

        return $array;
    }
}
