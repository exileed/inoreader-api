<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class Category extends AbstractObject implements ObjectInterface
{
    public function id(): string
    {
        return $this->data->id;
    }

    public function label(): string
    {
        return $this->data->label;
    }
}
