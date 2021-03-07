<?php

namespace AJ\Rest\Users\Interfaces;

interface JWTUserInterface {
    public function setName($name);

    public function setPassword($password);

    public function getLoginRoute();

    public function setLoginRoute($loginRoute);

    public function getCredentials();
}