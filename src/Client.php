<?php
namespace AJ\Rest;

use AJ\Rest\Helpers\Methods;
use Nyholm\Psr7\Factory\Psr17Factory;

final class Client extends Curl {

    public function __construct($apiAddress, $user = null)
    {
        parent::__construct($apiAddress, $user);
    }

    public function get(array $options = null)
    {
        $psrRequest = $this->prepareRequest(Methods::GET, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null);
        return $this->setOptionsFromRequest($this->curl,$psrRequest);
    }

    public function post(array $options = null)
    {
        $psrRequest = $this->prepareRequest(Methods::POST, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null, $options['body'] ?? null);
        return $this->setOptionsFromRequest($this->curl,$psrRequest);
    }

    public function put(array $options = null)
    {
        $psrRequest = $this->prepareRequest(Methods::PUT, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null, $options['body'] ?? null);
        return $this->setOptionsFromRequest($this->curl,$psrRequest);
    }

    public function delete(array $options = null)
    {
        $psrRequest = $this->prepareRequest(Methods::DELETE, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null, $options['body'] ?? null);
        return $this->setOptionsFromRequest($this->curl,$psrRequest);
    }

    public function head( array $options = null)
    {
        $psrRequest = $this->prepareRequest(Methods::HEAD, $options['headers'] ?? null, $options['route'] ?? null);
        return $this->setOptionsFromRequest($this->curl,$psrRequest);
    }
}