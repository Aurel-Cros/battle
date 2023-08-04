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
        $query = "SELECT * FROM `fighters` WHERE id = ?";
        $prep = $pdo->prepare($query);
        $prep->execute([$id]);
        $fighter = $prep->fetch(PDO::FETCH_ASSOC);
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

    if (!preg_match('/^[a-z0-9 ]+$/', $name)) {
        $new = false;
    } else {
        try {
            $sql = "INSERT INTO fighters (name, health, attack, mana, healRatio) VALUES (?,?,?,?,?)";
            $pdo->prepare($sql)->execute([$name, $health, $attack, $mana, $healR]);
            $new = $pdo->lastInsertId('fighters');
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    return $new;
}

/**
 * Insert a fight in the database and returns the id of the new entry
 */
function insertFight(array $fight): int
{
    $pdo = DbAccess::getInstance();

    $idFighter1 = $fight['id_fighter1'];
    $idFighter2 = $fight['id_fighter2'];
    if (!is_int($idFighter1) || !is_int($idFighter2))
        throw new Error('Incorrect input.');
    try {
        $sql = "INSERT INTO fights (fighter_id_1, fighter_id_2) VALUES (:id1, :id2)";
        $prep = $pdo->prepare($sql);
        $prep->execute([
            "id1" => $idFighter1,
            "id2" => $idFighter2
        ]);
        $new = $pdo->lastInsertId('fighters');
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $new;
}
function updateLogs(int $fightId, array $newLogs)
{
    $pdo = DbAccess::getInstance();
    try {
        $newLogs = json_encode($newLogs);
        $query = "UPDATE fights SET logs = :logs WHERE id = :id";
        $prep = $pdo->prepare($query);
        $success = $prep->execute([
            "logs" => $newLogs,
            "id" => $fightId
        ]);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    return $success ?? false;
}
function declareWinner(int $fightId, int $winnerId): bool
{
    $pdo = DbAccess::getInstance();

    try {
        $query = "UPDATE fights SET winner = :winner WHERE id = :fight";
        $prep = $pdo->prepare($query);
        $success = $prep->execute([
            "winner" => $winnerId,
            "fight" => $fightId
        ]);
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
        $prep = $pdo->prepare("SELECT * FROM `fights` WHERE id=?");
        $prep->execute([$id]);
        $fight = $prep->fetch(PDO::FETCH_ASSOC);
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
