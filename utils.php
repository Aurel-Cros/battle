<?php
function heal(array &$john, int $amount = 20): bool
{
    if ($john['mana'] >= $amount) {
        $john['mana'] -= $amount;
        $john['health'] = min($john['max-health'], $john['health'] + $john['max-health'] * $amount / 100);
        return true;
    }

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
