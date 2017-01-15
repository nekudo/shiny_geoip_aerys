<?php
define('PROJECT_ROOT', __DIR__ . '/../');

require_once PROJECT_ROOT . 'src/ShinyGeoip.php';
require_once PROJECT_ROOT . 'src/Action/Action.php';
require_once PROJECT_ROOT . 'src/Action/ShowHomepageAction.php';
require_once PROJECT_ROOT . 'src/Action/ShowLocationAction.php';
require_once PROJECT_ROOT . 'src/Domain/LocationDomain.php';
require_once PROJECT_ROOT . 'src/Responder/HttpResponder.php';
require_once PROJECT_ROOT . 'src/Responder/ShowLocationResponder.php';
require_once PROJECT_ROOT . 'src/Responder/ShowHomepageResponder.php';