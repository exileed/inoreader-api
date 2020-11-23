<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class AddSubscription extends AbstractObject implements ObjectInterface
{

    public function query(): string
    {
        return $this->data->query;
    }

    public function numResults(): int
    {
        return $this->data->numResults;
    }

    public function streamId(): string
    {
        return $this->data->streamId;
    }

    public function streamName(): string
    {
        return $this->data->streamName;
    }
}
