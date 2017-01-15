<?php
namespace Nekudo\ShinyGeoip\Responder;

use Aerys\Response;

class HttpResponder
{
    /**
     * @var Response $response
     */
    protected $response;

    /**
     * @var int $statusCode
     */
    protected $statusCode = 200;

    /**
     * @var string $payload The payload to be send to the client.
     */
    protected $payload = '';

    /**
     * @var array $statusMessages List of supported status codes/messages.
     */
    protected $statusMessages = [
        200 => 'OK',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
    ];

    /**
     * Injects aerys response.
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Responds with a 200 OK header.
     *
     * @param string $payload
     */
    public function found(string $payload = '')
    {
        $this->payload = $payload;
        $this->statusCode = 200;
        $this->respond();
    }

    /**
     * Responds with a 404 not found header.
     *
     * @param string $payload
     */
    public function notFound(string $payload = '')
    {
        $this->payload = $payload;
        $this->statusCode = 404;
        $this->respond();
    }

    /**
     * Responds with a 405 method not allowed header.
     *
     * @param string $payload
     */
    public function methodNotAllowed(string $payload = '')
    {
        $this->payload = $payload;
        $this->statusCode = 405;
        $this->respond();
    }

    /**
     * Responds with a 500 internal server error header.
     *
     * @param string $payload
     */
    public function error(string $payload = '')
    {
        $this->payload = $payload;
        $this->statusCode = 500;
        $this->respond();
    }

    /**
     * Echos out the response header and content.
     */
    protected function respond()
    {
        $this->response->setStatus($this->statusCode);
        $this->response->setReason($this->statusMessages[$this->statusCode]);
        $this->response->end($this->payload);
    }
}
