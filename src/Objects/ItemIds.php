<?php
declare(strict_types=1);
/**
 * @package inoreader-api
 *
 * @author  Dmitriy Kuts <me@exileed.com>
 * @link    http://exileed.com
 *
 */


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
        return $this->data->itemRefs;
    }

    public function continuation(): string
    {
        return $this->data->continuation;
    }

}