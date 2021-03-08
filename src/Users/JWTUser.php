<?php
namespace AJ\Rest\Users;

use AJ\Rest\Users\Interfaces\JWTUserInterface;

/**
 * Class JWTUser
 * @package AJ\Rest\Users
 */
class JWTUser implements JWTUserInterface {

    /**
     * JWTUser constructor.
     * @param array $name
     * @param array $password
     * @param string $loginRoute
     */
    public function __construct(
        private array | string $name,
        private array | string $password,
        private string $loginRoute,
        private string | null $token = null,)
    {}

    /**
    * @param array|string $name
    * ['field name' => ['name']
    */
    public function setName(string | array $name): void
    {
        $this->name = $name;
    }

    /**
    * @param array|string $password
    * ['field name' => ['password']
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
    * @param string $key
    */
    public function setTokenKey(string $key): void
    {
        $this->token = $key;
    }

    /**
     * @return string
     */
    public function getTokenKey(): string | null
    {
        return $this->token;
    }

    /**
     * @return array
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