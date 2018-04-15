<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Objects;


class Token extends AbstractObject implements ObjectInterface
{

    public function accessToken(): string
    {
        return $this->data->access_token;
    }

    public function tokenType(): string
    {
        return $this->data->token_type;
    }


    public function expiresIn()
    {
        return $this->data->expires_in;
    }

    public function refreshToken(): string
    {
        return $this->data->refresh_token;
    }

    public function scope(): string
    {
        return $this->data->scope;
    }

}