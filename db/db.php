<?php
require_once(__DIR__ . '/DbAccess.php');

function getAllFighters(): array
{
    $pdo = DbAccess::getInstance();
    try {
        $fighters = $pdo->query("SELECT * FROM fighters ORDER BY id")->fetchAll();
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    return $fighters;
}

function insertFighter(array $fighter): void
{
    $pdo = DbAccess::getInstance();
    try {
        $sql = "INSERT INTO users (name, health, attack, mana, healRatio) VALUES (:name, :health, :attack, :mana, :healRatio)";
        $pdo->prepare($sql)->execute($fighter);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
