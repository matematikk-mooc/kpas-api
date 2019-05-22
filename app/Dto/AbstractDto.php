<?php

namespace App\Dto;

abstract class AbstractDto
{
    public function __construct(array $data)
    {
        $this->fillData($data);
    }

    protected function fillData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{"set" . ucfirst($key)}($value);
            }
        }

    }
}
