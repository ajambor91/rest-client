<?php

namespace AJ\Rest\Users\Interfaces;

/**
 * Interface JWTUserInterface
 * @package AJ\Rest\Users\Interfaces
 */

interface JWTUserInterface {
    /**
     * @param array|string $name
     */
    public function setName(string | array $name): void;

    /**
     * @param array|string $password
     * @return array|string
     */
    public function setPassword(string | array $password): void;

    /**
     * @return string
     */
    public function getLoginRoute(): string;

    /**
     * @param string $loginRoute
     */
    public function setLoginRoute(string $loginRoute): void;

    /**
     * @return array
     */
    public function getCredentials(): array;

}