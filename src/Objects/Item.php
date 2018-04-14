<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;


class Item extends AbstractObject implements ObjectInterface
{

    public function crawlTimeMsec()
    {
        return $this->data->crawlTimeMsec;
    }

    public function timestampUsec()
    {
        return $this->data->timestampUsec;
    }

    public function categories(): array
    {
        return $this->data->categories;
    }

    public function title()
    {
        return $this->data->title;
    }

    public function published(): int
    {
        return $this->data->published;
    }

    public function updated(): int
    {
        return $this->data->updated;
    }

    public function id()
    {
        return $this->data->id;
    }

    public function canonical()
    {
        return $this->data->canonical;
    }

    public function alternate()
    {
        return $this->data->alternate;
    }

    public function summary(): Summary
    {
        return new Summary($this->data->summary);
    }

    public function author()
    {
        return $this->data->author;
    }

    public function likingUsers(): array
    {
        return $this->data->id;
    }

    public function comments(): array
    {
        return $this->data->comments;
    }

    public function commentsNum()
    {
        return $this->data->commentsNum;
    }

    public function annotations()
    {
        return $this->data->id;
    }

    public function origin()
    {
        return $this->data->id;
    }


}