<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;


class Tag extends AbstractObject implements ObjectInterface
{

    public function id(): string
    {
        return $this->data->id;
    }

    public function sortId(): string
    {
        return $this->data->sortid;
    }


}