<?php
require_once __DIR__ . '/src/bootstrap.php';

const AERYS_OPTIONS = [
    'user' => 'simon',
    'connectionsPerIP' => 100,
    'maxKeepAliveRequests' => 100,
    'disableKeepAlive' => true,
    'allowedMethods' => ['GET', 'HEAD'],
    'deflateEnable' => false,
    'maxFieldLen' => 100,
    'maxInputVars' => 10,
    'maxBodySize' => 4096,
    'maxHeaderSize' => 4096,
];

$geoip = new \Nekudo\ShinyGeoip\ShinyGeoip;
$docroot = Aerys\root(__DIR__ . '/../www');

// Default Host
(new Aerys\Host)
    ->name('localhost')
    ->expose('*', 80)
    ->use($docroot)
    ->use(function (Aerys\Request $req, Aerys\Response $res) use ($geoip) {
        $geoip->dispatch($req, $res);
    });

// SSL Host
(new Aerys\Host)
    ->name('localhost')
    ->expose('*', 443)
    ->encrypt(__DIR__ . '/cert/localhost.crt', __DIR__ . '/cert/localhost.key')
    ->use($docroot)
    ->use(function (Aerys\Request $req, Aerys\Response $res) use ($geoip) {
        $geoip->dispatch($req, $res);
    });