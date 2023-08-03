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

    return $fighters ? $fighters : ["empty" => true];
}

function getFighter(int $id): array
{
    $pdo = DbAccess::getInstance();
    try {
        $fighter = $pdo->query("SELECT * FROM `fighters` WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $fighter ? $fighter : ["empty" => true];
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

/**
 * Insert a fight in the database and returns the id of the new entry
 */
function insertFight(array $fight): int
{
    $pdo = DbAccess::getInstance();

    $idFighter1 = $fight['id-fighter1'];
    $idFighter2 = $fight['id-fighter2'];

    try {
        $sql = "INSERT INTO fights (fighter_id_1, fighter_id_2) VALUES ($idFighter1, $idFighter2)";
        $pdo->exec($sql);
        $new = $pdo->lastInsertId('fighters');
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $new;
}
function updateLogs(int $fightId, array $newLogs)
{
    $pdo = DbAccess::getInstance();
    $logs = json_encode($newLogs);
    try {
        $sql = "UPDATE fights SET logs = $logs WHERE id = $fightId";
        $success = $pdo->exec($sql) || false;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $success ?? false;
}
function declareWinner(int $fightId, int $winnerId): bool
{
    $pdo = DbAccess::getInstance();

    try {
        $sql = "UPDATE fights SET winner = $winnerId WHERE id = $fightId";
        $success = $pdo->exec($sql) || false;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $success ?? false;
}

function getAllFights(): array
{
    $pdo = \DbAccess::getInstance();
    try {
        $fights = $pdo->query("SELECT * FROM fights ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    return $fights ? $fights : ["empty" => true];
}

function getFight(int $id): array
{
    $pdo = DbAccess::getInstance();
    try {
        $fight = $pdo->query("SELECT * FROM `fights` WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $fight ? $fight : ["empty" => true];
}

function getWinsByFighter()
{
    $pdo = DbAccess::getInstance();
    try {
        $query = 'SELECT fighters.name, COUNT(winner) AS Wins
            FROM fights
            JOIN fighters
            ON fighters.id = fights.winner
            GROUP BY winner
            ORDER BY Wins DESC
        ;';

        $fight = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $fight ? $fight : ["empty" => true];
}
