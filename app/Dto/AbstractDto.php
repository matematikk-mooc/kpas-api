<?php

namespace App\Dto;

use Illuminate\Support\Str;

abstract class AbstractDto
{
    public function __construct(array $data)
    {
        $this->fillData($data);
    }

    protected function fillData(array $data): void
    {
        foreach ($data as $key => $value) {
            $camelKey = Str::camel($key);
            if (property_exists($this, $camelKey)) {
                $this->{"set" . ucfirst($camelKey)}($value);
            }
        }

    }
}
