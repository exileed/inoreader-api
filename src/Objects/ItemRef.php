<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;


class ItemRef extends AbstractObject implements ObjectInterface
{

    public function id(): string
    {
        return $this->data->id;
    }

    public function directStreamIds():array
    {
        return $this->data->directStreamIds;
    }

    public function timestampUsec():string
    {
        return $this->data->timestampUsec;
    }

}