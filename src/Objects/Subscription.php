<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class Subscription extends AbstractObject implements ObjectInterface
{

    public function id(): string
    {
        return $this->data->id;
    }

    public function title(): string
    {
        return $this->data->title;
    }

    public function feedType(): string
    {
        return $this->data->feedType;
    }

    /**
     * @return Category[]
     */
    public function categories(): array
    {
        $items = [];
        foreach ($this->data->categories as $category) {
            $items[] = new Category($category);
        }

        return $items;
    }

    public function sortId(): string
    {
        return $this->data->sortid;
    }

    public function firstItemMsec(): int
    {
        return $this->data->firstitemmsec;
    }

    public function url(): string
    {
        return $this->data->url;
    }

    public function htmlUrl(): string
    {
        return $this->data->htmlUrl;
    }

    public function iconUrl(): string
    {
        return $this->data->iconUrl;
    }
}
