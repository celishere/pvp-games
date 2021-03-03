<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\Player;

/**
 * Class Mode
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class TeamMode extends Mode {

    abstract public function getTeams(): array;

    abstract public function getPlayerTeam(Player $player): ?int;
}