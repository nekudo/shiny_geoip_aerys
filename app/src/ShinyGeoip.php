<?php
/**
 * ShinyGeoip
 *
 * by Simon Samtleben <simon@nekudo.com>
 *
 * A IP to location HTTP REST API.
 * For more information visit: https://github.com/nekudo/shiny_geoip
 */

namespace Nekudo\ShinyGeoip;

use Aerys\Request;
use Aerys\Response;
use Nekudo\ShinyGeoip\Action\ShowHomepageAction;
use Nekudo\ShinyGeoip\Action\ShowLocationAction;

class ShinyGeoip
{
    /**
     * Holds current route name.
     *
     * @var string $route
     */
    protected $route = '';

    /**
     * Holds request arguments.
     *
     * @var array $arguments
     */
    protected $arguments = [];

    /**
     * @var ShowHomepageAction $showHomepageAction
     */
    protected $showHomepageAction;

    /**
     * @var ShowLocationAction $showLocationAction
     */
    protected $showLocationAction;

    /**
     * @var Request $request
     */
    protected $request;

    public function __construct()
    {
        $this->showHomepageAction = new ShowHomepageAction;
        $this->showLocationAction = new ShowLocationAction;
    }

    /**
     * Routes the request and executes corresponding action.
     *
     * @param Request $request
     * @param Response $response
     */
    public function dispatch(Request $request, Response $response)
    {
        $this->request = $request;
        $this->reset();
        $this->route();
        switch ($this->route) {
            case 'api':
                $this->showLocationAction->setRequest($request);
                $this->showLocationAction->setResponse($response);
                $this->showLocationAction->__invoke($this->arguments);
                break;
            case 'home':
                $this->showHomepageAction->setRequest($request);
                $this->showHomepageAction->setResponse($response);
                $this->showHomepageAction->__invoke();
                break;
            default:
                $response->setStatus(404);
                $response->setReason('Not found');
                $response->end(':( Page not found.');
                break;
        }
    }

    /**
     * Sets route depending on request path.
     *
     * @return bool
     */
    protected function route() : bool
    {
        $urlPath = parse_url($this->request->getUri(), PHP_URL_PATH);
        if ($urlPath === false) {
            return false;
        }
        if ($urlPath === '/') {
            $this->route = 'home';
            return true;
        }
        if (substr($urlPath, 0, 4) === '/api') {
            $this->route = 'api';
            $this->parseArgumentsFromRequest($urlPath);
            return true;
        }
        return false;
    }

    /**
     * Collects known arguments out of request uri.
     *
     * @param string $urlPath
     */
    protected function parseArgumentsFromRequest($urlPath)
    {
        $pathParts = explode('/', $urlPath);
        foreach ($pathParts as $part) {
            if ($part === '' || $part === 'api') {
                continue;
            }
            // check for type:
            if ($part === 'full' || $part === 'short') {
                $this->arguments['type'] = $part;
                continue;
            }
            // check for ip:
            if (preg_match('/^[0-9a-f.:]{6,45}$/', $part) === 1) {
                $this->arguments['ip'] = $part;
                continue;
            }
            // check for language:
            if (preg_match('/^[a-z]{2}$/', $part) === 1) {
                $this->arguments['lang'] = $part;
                continue;
            }
        }

        // check for callback:
        $this->arguments['callback'] = $this->request->getParam('callback');
    }

    protected function reset()
    {
        $this->route = '';
        $this->arguments = [
            'ip' => '',
            'type' => 'short',
            'lang' => 'en',
            'callback' => '',
        ];
    }
}
