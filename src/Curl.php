<?php

namespace AJ\Rest;

use AJ\Rest\Exceptions\InvalidTokenException;
use AJ\Rest\Helpers\Methods;
use AJ\Rest\Exceptions\CallbackException;
use AJ\Rest\Exceptions\InvalidRequestException;
use AJ\Rest\Exceptions\NetworkException;
use AJ\Rest\Helpers\ValidateToken;
use AJ\Rest\Users\Interfaces\BasicUserInterface;
use AJ\Rest\Users\Interfaces\JWTUserInterface;
use Psr\Http\Message\RequestInterface;
use AJ\Rest\Helpers\Headers;
use AJ\Rest\Users\JWTUser;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Curl
 * @package AJ\Rest
 */
abstract class Curl
{
    use Request {
        Request::__construct as private __requestConstruct;
    }

    use Response {
        Response::__construct as protected __responseConstruct;
    }

    /**
     * @var
     */
    protected $curl;

    /**
     * Curl constructor.
     * @param string $apiAddr
     * @param BasicUserInterface|JWTUserInterface|null $user
     */
    public function __construct(protected string $apiAddr, JWTUserInterface|BasicUserInterface|null $user)
    {
        $this->__requestConstruct($user);
        $this->__responseConstruct();
        $this->initCurl();
    }

    /**
     * @param JWTUser $user
     * @return string
     * @throws InvalidTokenException
     */
    public function jwtLogin(JWTUser $user): string
    {
        $options[CURLOPT_URL] = $this->apiAddr . '/' . $user->getLoginRoute();
        $options[CURLOPT_CUSTOMREQUEST] = Methods::POST;
        $options[CURLOPT_POSTFIELDS] = json_encode($user->getCredentials());
        curl_setopt_array($this->curl, $options);
        $token = curl_exec($this->curl);

        return $token;
    }

    /**
     * @return ResponseInterface
     * @throws CallbackException
     * @throws InvalidRequestException
     * @throws NetworkException
     */
    protected function fire(): ResponseInterface
    {
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, function ($curl, $data) {
            $str = trim($data);
            if ('' !== $str) {
                if (str_contains(strtolower($str), 'http/') === true) {
                    $this->setStatus($str);
                } else {
                    $this->addHeader($str);
                }
            }
            return \strlen($data);
        });

        curl_setopt($this->curl, CURLOPT_WRITEFUNCTION, function ($curl, $data) {
            return $this->addBody($data);
        });

        try {
            curl_exec($this->curl);
            $this->checkErrors(curl_errno($this->curl));

        } finally {
            curl_close($this->curl);
        }

        return $this->getResponse();
    }

    /**
     * @param $curl
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws CallbackException
     * @throws InvalidRequestException
     * @throws NetworkException
     */
    protected function createRequest($curl, RequestInterface $request): ResponseInterface
    {
        $options = [
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_URL => $request->getUri()->__toString(),
            CURLOPT_HTTPHEADER => Headers::convertHeaders($request->getHeaders()),
        ];

        $options[CURLOPT_HTTP_VERSION] = $request->getProtocolVersion();

        if ($request->getUri()->getUserInfo()) {
            $options[CURLOPT_USERPWD] = $request->getUri()->getUserInfo();
        }
        switch ($request->getMethod()) {
            case Methods::HEAD:
                $options[CURLOPT_NOBODY] = true;
                break;
            case Methods::GET:
                break;
            case Methods::POST:
            case Methods::PUT:
            case Methods::DELETE:
            case Methods::PATCH:
            case Methods::OPTIONS:
                $options[CURLOPT_POSTFIELDS] = (string)$request->getBody();
                break;
        }

        curl_setopt_array($curl, $options);

        return $this->fire();
    }

    /**
     * @param int $errorNumber
     * @throws CallbackException
     * @throws InvalidRequestException
     * @throws NetworkException
     */
    private function checkErrors(int $errorNumber)
    {
        switch ($errorNumber) {
            case CURLE_OK:
                break;
            case CURLE_COULDNT_RESOLVE_PROXY:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_COULDNT_CONNECT:
            case CURLE_OPERATION_TIMEOUTED:
            case CURLE_SSL_CONNECT_ERROR:
                throw new NetworkException(curl_error($this->curl), $errorNumber);
            case CURLE_ABORTED_BY_CALLBACK:
                throw new CallbackException(curl_error($this->curl), $errorNumber);
            default:
                throw new InvalidRequestException(curl_error($this->curl), $errorNumber);
        }

    }

    private function initCurl()
    {
        $this->curl = curl_init();
        $options = [
            CURLOPT_USERAGENT => "AJ PHP Rest Client",
            CURLOPT_ENCODING => "UTF-8",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_MAXREDIRS => 10
        ];
        curl_setopt_array($this->curl, $options);
    }
}