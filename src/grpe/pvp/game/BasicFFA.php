<?php

declare(strict_types=1);

namespace grpe\pvp\game;

use pocketmine\math\Vector3;

/**
 * Class BasicFFA
 * @package grpe\pvp\game
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class BasicFFA extends FFAMode {

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
        $data = $this->getSession()->getData();

        $pos1 = $data->getPos1();
        $pos2 = $data->getPos2();

        $x = mt_rand($pos1->getX(), $pos2->getX());
        $z = mt_rand($pos1->getZ(), $pos2->getZ());

        return new Vector3($x, $pos1->getY(), $z); //Y один и тот же?
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
}