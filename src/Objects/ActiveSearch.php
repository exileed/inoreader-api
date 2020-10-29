<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class ActiveSearch extends AbstractObject
{
    public function id(): string
    {
        return $this->data->id;
    }

    public function title(): string
    {
        return $this->data->title;
    }
}
