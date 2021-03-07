<?php
namespace AJ\Rest;

use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Uri;
use AJ\Rest\Users\Interfaces\BasicUserInterface;
use AJ\Rest\Users\Interfaces\JWTUserInterface;

trait Request {

    private $method;
    private $uri;
    private $headers;
    private $body;
    private $version;
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    protected function prepareRequest($method = GET, $headers = null, $route = null,  $query = null, $body = null, $version = '1.1')
    {
        $this->uri  = $this->createUri($route, $query);
        $this->body = $this->createBody($body);
        $this->headers = $headers ?? ['Content-Type' => 'application/json'];
        $this->method = $method;
        $this->version = $version;
        $this->authUser();
        return $this->getRequest();
    }

    private function getRequest()
    {
        $psr17Factory = new \Nyholm\Psr7\Request($this->method, $this->uri, $this->headers);
        return  $psr17Factory->withBody($this->body);
    }

    private function createUri($path, $query)
    {
        return  (new Uri($this->apiAddr))
        ->withPath($path ?? '')
        ->withQuery(http_build_query($query ?? []));
    }

    private function createBody($body)
    {
        return Stream::create(json_encode($body ?: []));
    }

    private function authUser()
    {
        if($this->user == null) {
            return;
        } elseif ($this->user instanceof BasicUserInterface) {
            $this->uri = $this->uri->withUserInfo($this->user->getName(), $this->user->getPassword());
        } elseif ($this->user instanceof JWTUserInterface) {
            $token = $this->jwtLogin($this->user);
            $this->headers['Authorization'] = "Bearer fdfdf";
        }
    }
}