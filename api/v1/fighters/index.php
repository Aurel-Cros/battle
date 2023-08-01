<?php
require_once('../../../lib/utils.php');
$root = App\FilesManager::rootDirectory();
require_once($root . '/db/db.php');
require_once($root . '/vendor/autoload.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new-fighter'])) {
        $newFighter = json_decode($_POST['new-fighter']);
        insertFighter($newFighter);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $apiOutput = json_encode(getFighter($id));
    } else {
        $apiOutput = json_encode(getAllFighters());
    }
}

echo $apiOutput ?? null;
