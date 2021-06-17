<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;

use pocketmine\level\Position;
use pocketmine\math\Vector3;

use pocketmine\Player;

/**
 * Class BasicFFA
 * @package grpe\pvp\game
 *
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class BasicFFA extends FFAMode {

    protected array $kills = [];

    private GameSession $session;

    /**
     * BasicFFA constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->session = $session;
    }

    /**
     * @return Vector3
     */
    public function getPos(): Vector3 {

        //TODO: переделать под заданные точки

        $data = $this->getSession()->getData();

        $pos1 = $data->getPos1()->floor();
        $pos2 = $data->getPos2()->floor();

        $x = mt_rand($pos1->getX(), $pos2->getX());
        $z = mt_rand($pos1->getZ(), $pos2->getZ());

        return new Position($x, $pos1->getY(), $z, $this->getSession()->getLevel()); //Y один и тот же?
    }

    /**
     * @return GameSession
     */
    public function getSession(): GameSession {
        return $this->session;
    }

    /**
     * @return array
     */
    public function getItems(): array {
        return [];
    }

    /**
     * @return array
     */
    public function getArmor(): array {
        return [];
    }

    /**
     * @param Player $player
     */
    abstract public function respawnPlayer(Player $player): void;
}