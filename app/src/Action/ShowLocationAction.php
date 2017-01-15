<?php
namespace Nekudo\ShinyGeoip\Action;

use MaxMind\Db\Reader;
use Nekudo\ShinyGeoip\ShowLocationResponder;

class ShowLocationAction extends Action
{
    protected $responder;

    public function __construct()
    {
        parent::__construct();
        $this->responder = new ShowLocationResponder;
    }

    /**
     * Sends location data for requested IP to client.
     *
     * @param array $arguments
     * @return bool
     */
    public function __invoke(array $arguments)
    {
        $this->responder->setResponse($this->response);

        // fetch record for requested ip (use client ip if no ip provided):
        if (empty($arguments['ip'])) {
            $connectionInfo = $this->request->getConnectionInfo();
            $arguments['ip'] = $connectionInfo['client_addr'];
        }
        $record = $this->locationDomain->getRecord($arguments['ip']);

        // set requested callback method for JSONP responses:
        if (!empty($arguments['callback'])) {
            $this->responder->setCallback($arguments['callback']);
        }

        // if no record was found we respond with an error message:
        if (empty($record)) {
            $this->responder->recordNotFound();
            return false;
        }

        // shorten the record data to save traffic:
        if ($arguments['type'] === 'short') {
            $record = $this->locationDomain->shortenRecord($record, $arguments['lang']);
        }

        // send record data to client:
        $this->responder->recordFound($record);
        return true;
    }
}
