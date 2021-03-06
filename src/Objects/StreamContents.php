<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class StreamContents extends AbstractObject implements ObjectInterface
{

    public function direction(): string
    {
        return $this->data->direction;
    }

    public function id(): string
    {
        return $this->data->id;
    }

    public function title(): string
    {
        return $this->data->title;
    }

    public function description(): string
    {
        return $this->data->description;
    }

    public function self(): ItemSelf
    {
        return new ItemSelf($this->data->self);
    }

    public function updated(): int
    {
        return $this->data->updated;
    }

    public function updatedUsec(): string
    {
        return $this->data->updatedUsec;
    }

    public function continuation(): ?string
    {
        return $this->data->continuation ?? null;
    }

    /**
     * @return Item[]
     */
    public function items(): array
    {

        $items = [];
        foreach ($this->data->items as $item) {
            $items[] = new Item($item);
        }

        return $items;
    }
}
