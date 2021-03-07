<?php

namespace AJ\Rest;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Stream;

trait Response {

    private $response;
    private $responseFactory;
    private $parsedHeaders;

    public function __construct()
    {
        $this->responseFactory = new Psr17Factory();
        $this->response = $this->responseFactory->createResponse();
    }

    protected function setStatus($data)
    {
        $status = explode(' ', $data);
        $this->response = $this->response->withStatus($status[1]);
    }

    protected function addHeader($data)
    {
        list($key, $value) = explode(':', $data, 2);
        $this->response = $this->response->withAddedHeader(trim($key), trim($value));
    }

    protected function addBody($data)
    {
        return $this->response->getBody()->write($data);
    }

    protected function getResponse()
    {
        $this->response->getBody()->rewind();
        $response = $this->response;
        return $response;
    }
}