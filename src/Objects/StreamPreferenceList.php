<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class StreamPreferenceList extends AbstractObject implements ObjectInterface
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

    public function self()
    {
        return $this->data->self;
    }

    public function updated(): int
    {
        return $this->data->updated;
    }

    public function updatedUsec(): int
    {
        return $this->data->updatedUsec;
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
