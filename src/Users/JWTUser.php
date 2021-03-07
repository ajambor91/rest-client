<?php
namespace AJ\Rest\Users;

use AJ\Rest\Users\Interfaces\JWTUserInterface;

/**
 * Class JWTUser
 * @package AJ\Rest\Users
 */
class JWTUser implements JWTUserInterface {

    /**
     * @var array|string
     */
    private array | string $name;

    /**
     * @var array|string
     */
    private array | string $password;

    /**
     * @var string
     */
    private string $loginRoute;

    /**
     * JWTUser constructor.
     * @param array $name
     * @param array $password
     * @param string $loginRoute
     */
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
    public function setName(string | array $name): void
    {
        $this->name = $name;
    }

    /**
     * @param array $password
     */
    public function setPassword(array | string  $password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLoginRoute():string
    {
        return $this->loginRoute;
    }

    /**
     * @param mixed $loginRoute
     */
    public function setLoginRoute(string $loginRoute): void
    {
        $this->loginRoute = $loginRoute;
    }

    /**
     * @return array
     * ['postname' => 'value']
     */
    public function getCredentials():array
    {
        if(true === is_string($this->name)) {
            $this->name = [
                'login' => $this->name
            ];
        }
        if(true === is_string($this->password)){
            $this->password = [
                'password' => $this->password
            ];
        }
        return [
            $this->name,
            $this->password
        ];
    }
}