<?php

namespace tranber\services;

interface ClientInterface
{
    public static function getInstance();

    public function isLogged(): bool;

    public function logIn(iterable $user): ClientInterface;

    public function logOut(): ClientInterface;
}
