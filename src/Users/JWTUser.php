<?php
namespace AJ\Rest\Users;

use AJ\Rest\Users\Interfaces\JWTUserInterface;

class JWTUser implements JWTUserInterface {

    private $name;
    private $password;
    private $loginRoute;

    public function __construct(array $name, array $password, string $loginRoute)
    {
        $this->name = $name;
        $this->password = $password;
        $this->loginRoute = $loginRoute;
    }

    /**
     * @param array $name
     * ['field name' => ['password']
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param array $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLoginRoute()
    {
        return $this->loginRoute;
    }

    /**
     * @param mixed $loginRoute
     */
    public function setLoginRoute($loginRoute): void
    {
        $this->loginRoute = $loginRoute;
    }

    /**
     * @return array
     * ['postname' => 'value']
     */
    public function getCredentials()
    {
        return [
            $this->name,
            $this->password
        ];
    }




}