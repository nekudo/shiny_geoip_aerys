<?php
namespace Nekudo\ShinyGeoip\Action;

use Nekudo\ShinyGeoip\Responder\ShowHomepageResponder;

class ShowHomepageAction extends Action
{
    protected $responder;

    public function __construct()
    {
        parent::__construct();
        $this->responder = new ShowHomepageResponder;
    }

    /**
     * Shows homepage.
     */
    public function __invoke()
    {
        // fetch location record for users ip to display on homepage:
        $connectionInfo = $this->request->getConnectionInfo();
        $record = $this->locationDomain->getRecord($connectionInfo['client_addr']);
        if (!empty($record)) {
            $record = $this->locationDomain->shortenRecord($record, 'en');
        }

        // send response to browser:
        $this->responder->setResponse($this->response);
        $this->responder->showHomepage($record);
    }
}
