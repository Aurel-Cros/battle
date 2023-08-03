<?php
require_once('../../lib/utils.php');
$root = FilesManager::rootDirectory();
require_once($root . '/db/db.php');
require_once($root . '/vendor/autoload.php');

if (!isset($_GET['route'])) {
    http_response_code(404);
    exit();
}
$route = $_GET['route'];
$routeArray = explode('/', $route);

// At this point, we have route an array of folder levels
// and queryParameters an key value array of filters

if ($routeArray[0] === 'fighters') {
    include('./db_fighters/index.php');
} elseif ($routeArray[0] === 'fights') {
    include('./db_fights/index.php');
}
