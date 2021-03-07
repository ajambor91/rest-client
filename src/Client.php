<?php
namespace AJ\Rest;

use AJ\Rest\Helpers\Methods;
use AJ\Rest\Users\Interfaces\BasicUserInterface;
use AJ\Rest\Users\Interfaces\JWTUserInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 * @package AJ\Rest
 */
final class Client extends Curl {

    /**
     * Client constructor.
     * @param string $apiAddress
     * @param BasicUserInterface|JWTUserInterface|null $user
     */
    public function __construct(string $apiAddress, JWTUserInterface | BasicUserInterface | null $user = null)
    {
        parent::__construct($apiAddress, $user);
    }

    /**
     * @param array|null $options
     * @return ResponseInterface
     * @throws Exceptions\CallbackException
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\NetworkException
     */
    public function get(array $options = null): ResponseInterface
    {
        $psrRequest = $this->prepareRequest(Methods::GET, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null);
        return $this->createRequest($this->curl,$psrRequest);
    }

    /**
     * @param array|null $options
     * @return ResponseInterface
     * @throws Exceptions\CallbackException
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\NetworkException
     */
    public function post(array $options = null): ResponseInterface
    {
        $psrRequest = $this->prepareRequest(Methods::POST, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null, $options['body'] ?? null);
        return $this->createRequest($this->curl,$psrRequest);
    }

    /**
     * @param array|null $options
     * @return ResponseInterface
     * @throws Exceptions\CallbackException
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\NetworkException
     */
    public function put(array $options = null): ResponseInterface
    {
        $psrRequest = $this->prepareRequest(Methods::PUT, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null, $options['body'] ?? null);
        return $this->createRequest($this->curl,$psrRequest);
    }

    /**
     * @param array|null $options
     * @return ResponseInterface
     * @throws Exceptions\CallbackException
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\NetworkException
     */
    public function patch(array $options = null): ResponseInterface
    {
        $psrRequest = $this->prepareRequest(Methods::PATCH, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null, $options['body'] ?? null);
        return $this->createRequest($this->curl,$psrRequest);
    }

    /**
     * @param array|null $options
     * @return ResponseInterface
     * @throws Exceptions\CallbackException
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\NetworkException
     */
    public function delete(array $options = null): ResponseInterface
    {
        $psrRequest = $this->prepareRequest(Methods::DELETE, $options['headers'] ?? null, $options['route'] ?? null, $options['query'] ?? null, $options['body'] ?? null);
        return $this->createRequest($this->curl,$psrRequest);
    }

    /**
     * @param array|null $options
     * @return ResponseInterface
     * @throws Exceptions\CallbackException
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\NetworkException
     */
    public function head(array $options = null): ResponseInterface
    {
        $psrRequest = $this->prepareRequest(Methods::HEAD, $options['headers'] ?? null, $options['route'] ?? null);
        return $this->createRequest($this->curl,$psrRequest);
    }

    /**
     * @param array|null $options
     * @return ResponseInterface
     * @throws Exceptions\CallbackException
     * @throws Exceptions\InvalidRequestException
     * @throws Exceptions\NetworkException
     */
    public function options(array $options = null): ResponseInterface
    {
        $psrRequest = $this->prepareRequest(Methods::OPTIONS, $options['headers'] ?? null, $options['route'] ?? null);
        return $this->createRequest($this->curl,$psrRequest);
    }

}