<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;


class AbstractObject
{

    /**
     * @var object
     */
    protected $data;

    /**
     * Constructor.
     *
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data)
    {
        $this->data = $data;
    }


}