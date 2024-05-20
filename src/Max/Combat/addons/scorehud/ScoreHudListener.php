<?php

declare(strict_types=1);

namespace Max\Combat\addons\scorehud;

use Ifera\ScoreHud\event\TagsResolveEvent;
use Max\Combat\Combat;
use Max\Combat\events\CombatCooldownStartEvent;
use pocketmine\event\Listener;

final class ScoreHudListener implements Listener {
    public function onTagResolve(TagsResolveEvent $event): void {
        $tag = $event->getTag();
        if ($tag->getName() === "combat.cooldown") {
            $tag->setValue("0");
        }
    }

    public function onCombatStart(CombatCooldownStartEvent $event): void {
        if ($event->isStart()) {
            Combat::getInstance()->getScheduler()->scheduleRepeatingTask(new ScoreHudTask($event->getPlayer()), 20);
        }
    }
}