<?php
require_once('../../../lib/utils.php');
$root = FilesManager::rootDirectory();
require_once($root . '/db/db.php');
require_once($root . '/vendor/autoload.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new-fight'])) {
        $newFight = json_decode($_POST['new-fight']);
        $newId = insertFight($newFight);

        if ($newId) {
            echo $newId;
            http_response_code(201);
        } else {
            http_response_code(204);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $apiOutput = json_encode(getFighter($id));
    } else {
        $apiOutput = json_encode(getAllFighters());
    }
    if (!empty($apiOutput)) {
        http_response_code(200);
        echo $apiOutput;
    } else
        http_response_code(204);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    // 
}
