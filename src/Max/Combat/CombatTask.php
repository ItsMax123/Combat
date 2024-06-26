<?php

declare(strict_types=1);

namespace Max\Combat;

use Max\Combat\events\CombatCooldownStopEvent;
use Max\Combat\events\CombatCooldownUpdateEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class CombatTask extends Task {
    private Player $player;
    private Session $session;

    public function __construct(Player $player) {
        $this->player = $player;
        $this->session = Combat::getInstance()->getSession($this->player);
    }

    public function onRun(): void {
        if ($this->player->isConnected()) {
            if ($this->session->isInCombat()) {
                if ($this->session->getCombatCooldownTimeLeft() % 20 === 0) {
                    (new CombatCooldownUpdateEvent($this->player, $this->session->getCombatCooldownTimeLeft()))->call();
                }
                return;
            }
            (new CombatCooldownStopEvent($this->player))->call();
            $this->player->sendMessage(Combat::getInstance()->getMessage("cooldown-stop"));
        }
        $this->getHandler()->cancel();
    }
}
