<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class SingleUnreadCount extends AbstractObject implements ObjectInterface
{
    public function id(): string
    {
        return $this->data->id;
    }

    public function count(): int
    {
        return $this->data->count;
    }

    public function newestItemTimestampUsec(): string
    {
        return $this->data->newestItemTimestampUsec;
    }
}
