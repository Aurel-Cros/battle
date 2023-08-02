<?php
require_once('DbAccess.php');


function getAllFighters(): array
{
    $pdo = \DbAccess::getInstance();
    try {
        $fighters = $pdo->query("SELECT * FROM fighters ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    return $fighters;
}

function getFighter(int $id): array
{
    $pdo = DbAccess::getInstance();
    try {
        $fighter = $pdo->query("SELECT * FROM `fighters` WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $fighter;
}

/**
 * Insert a fighter in the database and returns the id of the new entry
 */
function insertFighter(array $fighter): int
{
    $pdo = DbAccess::getInstance();

    $name = str_replace(' ', ' ', $fighter['name']);
    $health = $fighter['health'];
    $attack = $fighter['attack'];
    $mana = $fighter['mana'];
    $healR = $fighter['healRatio'];

    try {
        $sql = "INSERT INTO fighters (name, health, attack, mana, healRatio) VALUES (?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$name, $health, $attack, $mana, $healR]);
        $new = $pdo->lastInsertId('fighters');
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $new;
}
