<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class UnreadCount extends AbstractObject implements ObjectInterface
{

    public function max(): int
    {
        return $this->data->max;
    }

    /**
     * @return SingleUnreadCount[]
     */
    public function unreadCounts(): array
    {
        $counts = [];
        foreach ($this->data->unreadcounts as $count) {
            $counts[] = new SingleUnreadCount($count);
        }
        return $counts;
    }
}
