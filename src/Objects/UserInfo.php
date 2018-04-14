<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;

class UserInfo extends AbstractObject implements ObjectInterface
{


    public function userId(): string
    {
        return $this->data->userId;
    }

    public function userName(): string
    {
        return $this->data->userName;
    }


    public function userProfileId(): string
    {
        return $this->data->userProfileId;
    }

    public function userEmail(): string
    {
        return $this->data->userEmail;
    }

    public function isBloggerUser(): bool
    {
        return $this->data->isBloggerUser;
    }

    public function signupTimeSec(): string
    {
        return $this->data->signupTimeSec;
    }

    public function isMultiLoginEnabled(): bool
    {
        return $this->data->isMultiLoginEnabled;
    }

}

