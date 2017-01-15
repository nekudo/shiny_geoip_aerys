<?php
namespace Nekudo\ShinyGeoip\Action;

use Aerys\Request;
use Aerys\Response;
use Nekudo\ShinyGeoip\Domain\LocationDomain;

class Action
{
    /**
     * @var Request $request
     */
    protected $request;

    /**
     * @var Response $response
     */
    protected $response;

    /**
     * @var LocationDomain $locationDomain
     */
    protected $locationDomain;

    public function __construct()
    {
        $this->locationDomain = new LocationDomain;
    }

    /**
     * Injects aerys request.
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Injects aerys response.
     *
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
