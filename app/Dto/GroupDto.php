<?php

namespace App\Dto;

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
}
