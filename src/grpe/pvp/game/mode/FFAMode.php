<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;

use pocketmine\Player;

use pocketmine\item\Item;

use pocketmine\math\Vector3;

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
     * @return Vector3
     */
    abstract public function getPos(): Vector3;

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