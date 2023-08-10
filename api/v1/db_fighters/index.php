<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim(file_get_contents("php://input"));

    if (empty($content))
        return;

    $newFighter = json_decode($content, true);
    $newId = insertFighter($newFighter);

    echo $newId ?? null;
    http_response_code($newId ? 201 : 400);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($routeArray[1]) && !empty($routeArray[1])) {
        $id = intval($routeArray[1]);
        $apiOutput = json_encode(getFighter($id));
    } else {
        $apiOutput = json_encode(getAllFighters());
    }
    http_response_code(200);
    echo $apiOutput;
}
