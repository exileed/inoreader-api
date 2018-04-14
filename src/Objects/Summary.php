<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;


class Summary extends AbstractObject implements ObjectInterface
{

    public function direction()
    {
        return $this->data->direction;
    }

    public function content()
    {
        return $this->data->content;
    }


}