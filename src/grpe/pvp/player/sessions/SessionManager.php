<?php

declare(strict_types=1);

namespace grpe\pvp\player\sessions;

use pocketmine\Player;

/**
 * Class SessionManager
 * @package grpe\pvp\player\sessions
 *
 * @version 1.0.0
 * @since   1.0.0
 */
final class SessionManager {

    /** @var Session[] */
    private array $sessions = [];

    /**
     * @param Player $player
     * @return Session
     */
    public function getSession(Player $player): Session {
        if (!isset($this->sessions[$player->getLowerCaseName()])) {
            $this->sessions[$player->getLowerCaseName()] = new Session($player);
        }

        return $this->sessions[$player->getLowerCaseName()];
    }

    /**
     * @param Player $player
     */
    public function removeSession(Player $player): void {
        $this->getSession($player)->onSave();

        unset($this->sessions[$player->getLowerCaseName()]);
    }
}