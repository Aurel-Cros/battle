<?php

namespace App;

class FilesManager
{
    public static function rootDirectory()
    {
        // Change the second parameter to suit your needs
        return dirname(__FILE__, 2);
    }
}

/**
 * Refills fighters "john"'s health by a percentage of their max health, and consumes that much mana in exchange.
 * Returns true if healing is applied, or false if mana is depleted.
 * 
 * @param array &$john
 * @param int $amount
 * @return int
 */
function heal(array &$john): int | bool
{
    if ($john['mana'] >= $john['healRatio']) {
        $john['mana'] -= $john['healRatio'];
        $john['health'] = round(min($john['maxHealth'], $john['health'] + $john['maxHealth'] * $john['healRatio'] / 100));
        return $john['healRatio'];
    } else
        return false;
}

function areInputsValid(array $player, array $opponent): bool
{
    $valid = true;

    if (
        empty($player['name']) ||
        $player['health'] <= 0 ||
        $player['mana'] <= 0 ||
        $player['attack'] <= 0
    ) {
        $valid = false;
    }

    if (
        empty($opponent['name']) ||
        $opponent['health'] <= 0 ||
        $opponent['mana'] <= 0 ||
        $opponent['attack'] <= 0
    ) {
        $valid = false;
    }

    return $valid;
}

function getInputErrors(array $player, array $opponent): array
{
    $allErrors = [];
    $errorsList = [];

    if (empty($player['name'])) {
        $errorsList[] = 'Player name cannot be empty.';
        $allErrors['player-name-empty'] = true;
    }
    if ($player['health'] < 1) {
        $errorsList[] = 'Player health needs to be 1 or higher.';
        $allErrors['player-health-invalid'] = true;
    }
    if ($player['mana'] < 1) {
        $errorsList[] = 'Player mana needs to be 1 or higher.';
        $allErrors['player-mana-invalid'] = true;
    }
    if ($player['attack'] < 1) {
        $errorsList[] = 'Player attack needs to be 1 or higher.';
        $allErrors['player-attack-invalid'] = true;
    }

    if (empty($opponent['name'])) {
        $errorsList[] = 'Opponent name cannot be empty.';
        $allErrors['opponent-name-empty'] = true;
    }
    if ($opponent['health'] < 1) {
        $errorsList[] = 'Opponent health needs to be 1 or higher.';
        $allErrors['opponent-health-invalid'] = true;
    }
    if ($opponent['mana'] < 1) {
        $errorsList[] = 'Opponent mana needs to be 1 or higher.';
        $allErrors['opponent-mana-invalid'] = true;
    }
    if ($opponent['attack'] < 1) {
        $errorsList[] = 'Opponent attack needs to be 1 or higher.';
        $allErrors['opponent-attack-invalid'] = true;
    }

    $allErrors['ErrorsList'] = $errorsList;
    return $allErrors;
}
