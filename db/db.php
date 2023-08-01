<?php
require_once(__DIR__ . '/DbAccess.php');

function getAllFighters(): array
{
    $pdo = DbAccess::getInstance();
    $fighters = $pdo->query("SELECT * FROM fighters ORDER BY id")->fetchAll();
    var_dump($fighters);
    return $fighters;
}
