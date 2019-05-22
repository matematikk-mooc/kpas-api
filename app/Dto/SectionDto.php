<?php

namespace App\Dto;

class SectionDto extends AbstractDto
{
    /** @var string|null */
    protected $name;

    /** @var int|null */
    protected $id;

    /** @var int|null */
    protected $courseId;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
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
