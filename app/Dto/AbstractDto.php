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

    public function toArray(): array
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) {
            $array[Str::snake($key)] = $value;
        }

        return $array;
    }
}
