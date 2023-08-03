<?php
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
        } else
            http_response_code(204);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($routeArray[1]) && !empty($routeArray[1])) {
        $ressource = $routeArray[1];
        if ($ressource === 'number-of-wins')
            $apiOutput = json_encode(getWinsByFighter());
        else {
            $id = intval($ressource);
            $apiOutput = json_encode(getFight($id));
        }
    } else
        $apiOutput = json_encode(getAllFights());

    if (!empty($apiOutput)) {
        http_response_code(200);
        echo $apiOutput;
    } else
        http_response_code(204);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PATCH') {

    if (isset($routeArray[2], $_POST['fight-id'])) {

        $ressource = $routeArray[2];
        $fight = intval($_POST['fight-id']);

        if ($ressource === 'winner' && isset($_POST['fighter-id'])) {

            $fighter = intval($_POST['fighter-id']);

            declareWinner($fight, $fighter);
            http_response_code(201);
        } elseif ($ressource === 'logs' && isset($_POST['new-logs'])) {

            $newLogs = $_POST['new-logs'];
            updateLogs($fight, $newsLogs);
            http_response_code(201);
        } else
            http_response_code(400);
    } else
        http_response_code(400);
}
