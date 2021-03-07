<?php

namespace AJ\Rest\Users\Interfaces;

/**
 * Interface BasicUserInterface
 * @package AJ\Rest\Users\Interfaces
 */
interface BasicUserInterface {
    /**
     * @param string $password
     */
    public function setPassword(string $password ): void;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getName():string;
 }