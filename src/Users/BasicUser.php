<?php
namespace AJ\Rest\Users;

use AJ\Rest\Users\Interfaces\BasicUserInterface;

class BasicUser implements BasicUserInterface {

    private $name;
    private $password;

    public function __construct(string $name = null, string $password = null)
    {
        $this->name = $name;
        $this->password = $password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}