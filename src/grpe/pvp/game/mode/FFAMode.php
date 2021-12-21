<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;

use pocketmine\Player;

use pocketmine\item\Item;

use pocketmine\level\Position;

/**
 * Class FFAMode
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.1
 * @since   1.0.0
 */
abstract class FFAMode {

    /**
     * @return GameSession
     */
    abstract public function getSession(): GameSession;

    /**
     * @return Position
     */
    abstract public function getPos(): Position;

    /**
     * @return Item[]
     */
    abstract public function getItems(): array;

    /**
     * @return Item[]
     */
    abstract public function getArmor(): array;

    /**
     * @param int $stageId
     */
    public function onStageChange(int $stageId): void {}

    /**
     * @param Player $player
     */
    abstract public function respawnPlayer(Player $player): void;

    abstract public function tick(): void;
}