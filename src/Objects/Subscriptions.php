<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Objects;

class Subscriptions extends AbstractObject implements ObjectInterface
{


    /**
     * @return Subscription[]
     */
    public function subscriptions(): array
    {

        $items = [];
        foreach ($this->data->subscriptions as $subscription) {
            $items[] = new Subscription($subscription);
        }

        return $items;
    }
}
