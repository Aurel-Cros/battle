<?php
function heal(&$john, $amount = 20)
{
    if ($john['mana'] >= $amount) {
        $john['mana'] -= $amount;
        $john['sante'] = min($john['max-health'], $john['sante'] + $john['max-health'] * $amount / 100);
        return true;
    }

    return false;
}
