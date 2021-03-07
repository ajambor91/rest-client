<?php

namespace AJ\Rest;

use AJ\Rest\Exceptions\InvalidTokenException;
use AJ\Rest\Helpers\Methods;
use AJ\Rest\Exceptions\CallbackException;
use AJ\Rest\Exceptions\InvalidRequestException;
use AJ\Rest\Exceptions\NetworkException;
use AJ\Rest\Helpers\ValidateToken;
use Psr\Http\Message\RequestInterface;
use AJ\Rest\Helpers\Headers;
use AJ\Rest\Users\JWTUser;

abstract class Curl
{
    use Request {
        Request::__construct as private __requestConstruct;
    }

    use Response {
        Response::__construct as protected __responseConstruct;
    }

    protected $curl;
    protected $apiAddr;
    protected $request;
    private $psrRequest;

    public function __construct($apiAddr, $user)
    {
        $this->__requestConstruct($user);
        $this->__responseConstruct();
        $this->initCurl();
        $this->apiAddr = $apiAddr;
    }

    public function jwtLogin(JWTUser $user)
    {
        $options[CURLOPT_URL] = $this->apiAddr . '/' . $user->getLoginRoute();
        $options[CURLOPT_CUSTOMREQUEST] = Methods::POST;
        $options[CURLOPT_POSTFIELDS] = json_encode($user->getCredentials());
        curl_setopt_array($this->curl, $options);
        $token = curl_exec($this->curl);
        if (!ValidateToken::validateToken($token)){
            throw new InvalidTokenException();
        }
        return $token;
    }

    protected function fire()
    {
        curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, function ($ch, $data) {
            $str = trim($data);
            if ('' !== $str) {
                if (0 === strpos(strtolower($str), 'http/')) {
                    $this->setStatus($str);
                } else {
                    $this->addHeader($str);
                }
            }
            return \strlen($data);
        });

        curl_setopt($this->curl, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
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

    protected function setOptionsFromRequest($curl, RequestInterface $request): \Psr\Http\Message\ResponseInterface
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
                $body = $request->getBody();
                $bodySize = $body->getSize();
                if (0 !== $bodySize) {
                    if ($body->isSeekable()) {
                        $body->rewind();
                    }
                    if (null === $bodySize || $bodySize > 1024 * 1024) {
                        $options[CURLOPT_UPLOAD] = true;
                        if (null !== $bodySize) {
                            $options[CURLOPT_INFILESIZE] = $bodySize;
                        }
                        $options[CURLOPT_READFUNCTION] = function ($ch, $fd, $length) use ($body) {
                            return $body->read($length);
                        };
                    } else {
                        $options[CURLOPT_POSTFIELDS] = (string)$body;
                    }
                }
        }

        curl_setopt_array($curl, $options);

        return $this->fire();
    }

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
        curl_setopt($this->curl, CURLOPT_USERAGENT, "AJ PHP Rest Client");
        curl_setopt($this->curl, CURLOPT_ENCODING, "UTF-8");
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
    }
}