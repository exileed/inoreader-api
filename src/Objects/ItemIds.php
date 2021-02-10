<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class ItemIds extends AbstractObject implements ObjectInterface
{

    public function items(): array
    {
        return $this->data->items;
    }

   /**
     * @return ItemRef[]
     */
    public function itemRefs(): array
    {
        return array_map(fn($item) => new ItemRef($item), $this->data->itemRefs);
    }

    public function continuation(): ?string
    {
        return $this->data->continuation;
    }
}
