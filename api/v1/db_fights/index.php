<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PATCH");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $content = trim(file_get_contents("php://input"));
    if (!empty($content)) {

        $newFight = json_decode($content, true);
        $newId = insertFight($newFight);

        if ($newId) {
            echo '{"newFightId":"' . $newId . '"}';
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

    $content = trim(file_get_contents("php://input"));

    if (isset($routeArray[2]) && !empty($content)) {

        $content = json_decode($content, true);
        $ressource = $routeArray[2];
        $fight = intval($routeArray[1]);

        if ($ressource === 'winner' && isset($content['fighterId'])) {

            $fighter = intval($content['fighterId']);

            if (declareWinner($fight, $fighter))
                http_response_code(204);
            else
                http_response_code(500);
        } elseif ($ressource === 'logs' && isset($content['newLogs'])) {

            $newLogs = $content['newLogs'];
            $res = updateLogs($fight, $newLogs);
            echo $res, $fight;
            if ($res)
                http_response_code(204);
            else
                http_response_code(500);
        } else
            http_response_code(400);
    } else
        http_response_code(400);
}
