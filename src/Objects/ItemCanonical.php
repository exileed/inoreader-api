<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class ItemCanonical extends AbstractObject implements ObjectInterface
{
    public function href(): string
    {
        return $this->data->href;
    }
}
