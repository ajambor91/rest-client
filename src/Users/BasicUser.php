<?php
namespace AJ\Rest\Users;

use AJ\Rest\Users\Interfaces\BasicUserInterface;

/**
 * Class BasicUser
 * @package AJ\Rest\Users
 */
class BasicUser implements BasicUserInterface {

    /**
     * @var string|null
     */
    private ?string $name;
    /**
     * @var string|null
     */
    private ?string $password;

    /**
     * BasicUser constructor.
     * @param string|null $name
     * @param string|null $password
     */
    public function __construct(string $name = null, string $password = null)
    {
        $this->name = $name;
        $this->password = $password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}