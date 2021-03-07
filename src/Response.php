<?php

namespace AJ\Rest;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait Response
 * @package AJ\Rest
 */
trait Response {

    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    /**
     * @var Psr17Factory
     */
    private Psr17Factory $responseFactory;

    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->responseFactory = new Psr17Factory();
        $this->response = $this->responseFactory->createResponse();
    }

    /**
     * @param string $data
     */
    protected function setStatus(string $data)
    {
        $status = explode(' ', $data);
        $this->response = $this->response->withStatus($status[1]);
    }

    /**
     * @param string $data
     */
    protected function addHeader(string $data)
    {
        list($key, $value) = explode(':', $data, 2);
        $this->response = $this->response->withAddedHeader(trim($key), trim($value));
    }

    /**
     * @param string $data
     * @return int
     */
    protected function addBody(string $data): int
    {
        return $this->response->getBody()->write($data);
    }

    /**
     * @return ResponseInterface
     */
    protected function getResponse(): ResponseInterface
    {
        $this->response->getBody()->rewind();
        $response = $this->response;
        return $response;
    }
}