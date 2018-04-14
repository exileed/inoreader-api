<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;


class UnreadCount extends AbstractObject implements ObjectInterface
{

    public function max(): string
    {
        return $this->data->max;
    }

    public function unreadcounts()
    {
        return $this->data->unreadcounts;
    }


}