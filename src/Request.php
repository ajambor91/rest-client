<?php
namespace AJ\Rest;

use Nyholm\Psr7\MessageTrait;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\Uri;
use AJ\Rest\Users\Interfaces\BasicUserInterface;
use AJ\Rest\Users\Interfaces\JWTUserInterface;
use Psr\Http\Message\StreamInterface;
use Nyholm\Psr7\Request as Message;

/**
 * Trait Request
 * @package AJ\Rest
 */
trait Request {

    /**
     * @var string
     */
    private string $method;

    /**
     * @var Uri
     */
    private Uri $uri;

    /**
     * @var array
     */
    private array $headers;

    /**
     * @var StreamInterface
     */
    private StreamInterface $body;

    /**
     * @var string
     */
    private string $version;

    /**
     * @var BasicUserInterface|JWTUserInterface|null
     */
    private JWTUserInterface | BasicUserInterface | null $user;

    /**
     * Request constructor.
     * @param $user
     */
    public function __construct(BasicUserInterface | JWTUserInterface | null $user)
    {
        $this->user = $user;
    }

    /**
     * @param string $method
     * @param array|null $headers
     * @param string|null $route
     * @param array|null $query
     * @param array|null $body
     * @param string $version
     * @return MessageTrait
     */
    protected function prepareRequest(string $method = GET, array$headers = null, string $route = null, array $query = null, array $body = null, string $version = '1.1')
    {
        $this->uri  = $this->createUri($route, $query);
        $this->body = $this->createBody($body);
        $this->headers = $headers ?? ['Content-Type' => 'application/json'];
        $this->method = $method;
        $this->version = $version;
        $this->authUser();
        return $this->getRequest();
    }

    /**
     * @return Message
     */
    private function getRequest(): Message
    {
        $psr17Factory = new \Nyholm\Psr7\Request($this->method, $this->uri, $this->headers);
        return  $psr17Factory->withBody($this->body);
    }

    /**
     * @param string $path
     * @param array $query
     * @return Uri
     */
    private function createUri(string | null $path, array | null $query): Uri
    {
        return  (new Uri($this->apiAddr))
        ->withPath($path ?? '')
        ->withQuery(http_build_query($query ?? []));
    }

    /**
     * @param array $body
     * @return StreamInterface
     */
    private function createBody(array | null $body): StreamInterface
    {
        return Stream::create(json_encode($body ?: []));
    }

    /**
     *
     */
    private function authUser()
    {
        if($this->user == null) {
            return;
        } elseif ($this->user instanceof BasicUserInterface) {
            $this->uri = $this->uri->withUserInfo($this->user->getName(), $this->user->getPassword());
        } elseif ($this->user instanceof JWTUserInterface) {
            $token = $this->jwtLogin($this->user);
            $this->headers['Authorization'] = "Bearer $token";
        }
    }
}