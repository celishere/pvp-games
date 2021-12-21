<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\Team;

use pocketmine\Player;
use pocketmine\item\Item;

use pocketmine\math\Vector3;
use pocketmine\level\Location;

/**
 * Class Mode
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.2
 * @since   1.0.0
 */
abstract class Mode {

    /** @var Team[] */
    protected $teams = [];

    public function initTeams(): void {
        $spawns = $this->getSession()->getData()->getSpawns();

        for ($teamId = 1; $teamId <= 2; $teamId++) {
            $teamSpawns = [];

            foreach ($spawns[$teamId] as $spawn) {
                /** @var Location $spawn */
                $teamSpawns[] = $spawn->setLevel($this->getSession()->getLevel());
            }

            $this->teams[$teamId] = new Team($teamId, $teamSpawns);
        }
    }

    /**
     * @return GameSession
     */
    abstract public function getSession(): GameSession;

    /**
     * @return Team[]
     */
    public function getTeams(): array {
        return $this->teams;
    }

    /**
     * @param Player $player
     *
     * @return Team|null
     */
    public function getPlayerTeam(Player $player): ?Team {
        foreach ($this->getTeams() as $team) {
            if ($team->findPlayer($player->getId()) != null) {
                return $team;
            }
        }

        return null;
    }

    /**
     * @param int $id
     *
     * @return Team|null
     */
    public function getTeam(int $id): ?Team {
        return $this->teams[$id];
    }

    /**
     * @param Player $player
     *
     * @return Team|null
     */
    public function getOpponentTeam(Player $player): ?Team {
        $team = $this->getPlayerTeam($player);

        if ($team != null) {
            $opponentId = $team->getId() === 2 ? 1 : 2;
            return $this->getTeam($opponentId);
        }

        return null;
    }

    /**
     * @param Player $player
     *
     * @return Vector3
     */
    public function getSpawn(Player $player): Vector3 {
        $team = $this->getPlayerTeam($player);

        return $team->getPlayerSpawn($player);
    }

    /**
     * @param int $stageId
     */
    abstract public function onStageChange(int $stageId): void;

    /**
     * @param Team|null $team
     */
    public function checkTeam(?Team $team): void {
        if ($team != null) {
            if (count($team->getPlayers()) === 0) {
                unset($this->teams[$team->getId()]);
            }
        }
    }

    /**
     * @return Team
     */
    public function pickTeam(): Team {
        $selectedTeam = null;

        foreach ($this->getTeams() as $team) {
            if ($selectedTeam === null) {
                $selectedTeam = $team;
                continue;
            }

            if (count($team->getPlayers()) < count($selectedTeam->getPlayers())) {
                $selectedTeam = $team;
            }
        }

        return $selectedTeam;
    }

    /**
     * @return Item[]
     */
    abstract public function getItems(): array;

    /**
     * @return Item[]
     */
    abstract public function getArmor(): array;
}