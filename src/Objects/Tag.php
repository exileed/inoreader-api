<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class Tag extends AbstractObject implements ObjectInterface
{
    public const TYPE_ITEM = 1;
    public const TYPE_TAG = 'tag';
    public const TYPE_FOLDER = 'folder';
    public const TYPE_ACTIVE_SEARCH = 'active_search';

    public function id(): string
    {
        return $this->data->id;
    }

    public function sortId(): string
    {
        return $this->data->sortid;
    }

    public function unreadCount(): int
    {
        return $this->data->unread_count;
    }

    public function unseenCount(): int
    {
        return $this->data->unseen_count;
    }

    public function type(): string
    {
        return $this->data->type;
    }
}
