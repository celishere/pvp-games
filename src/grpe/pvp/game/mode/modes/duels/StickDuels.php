<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes\duels;

use grpe\pvp\Main;

use grpe\pvp\game\Stage;
use grpe\pvp\game\GameSession;

use grpe\pvp\game\mode\BasicDuels;

use grpe\pvp\game\task\RemoveCachedBlocks;

use grpe\pvp\utils\Utils;

use pocketmine\utils\TextFormat;

/**
 * Class StickDuels
 * @package grpe\pvp\game\mode\modes\duels
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class StickDuels extends BasicDuels {

    private array $scores = [1 => 0, 2 => 0];

    private array $cachedBlocks = [];

    /**
     * StickDuels constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        parent::__construct($session);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     * @param int $id
     * @param int $meta
     */
    public function addCachedBlock(int $x, int $y, int $z, int $id, int $meta = 0): void {
        $this->cachedBlocks[Utils::packXYZ($x, $y, $z)] = [$id, $meta];
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     *
     * @return bool
     */
    public function isBlockCached(int $x, int $y, int $z): bool {
        return isset($this->cachedBlocks[Utils::packXYZ($x, $y, $z)]);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     */
    public function removeCachedBlock(int $x, int $y, int $z): void {
        unset($this->cachedBlocks[Utils::packXYZ($x, $y, $z)]);
    }

    /**
     * @return array
     */
    public function getCachedBlocks(): array {
        return $this->cachedBlocks;
    }

    /**
     * @return array|int[]
     */
    public function getScores(): array {
        return $this->scores;
    }

    /**
     * @param int $teamId
     */
    public function addScore(int $teamId): void {
        $this->scores[$teamId]++;

        $this->onReset();

        if ($this->scores[$teamId] >= 5) {
            $this->checkTeam($this->getTeam($teamId));
            $this->getSession()->setStage(Stage::ENDING);

            $message = null;

            foreach ($this->getTeams() as $team) {
                $message = '&f' . (count($team->getPlayers()) > 1 ? 'Победители' : 'Победитель') . ': &7' . implode('&8, &7', array_map(function ($player): string {
                        return $player->getName();
                    }, $team->getPlayers()));
            }

            foreach ($this->getSession()->getPlayers() as $players) {
                $players->sendTitle(TextFormat::RED . 'Игра окончена.');

                if ($message != null) {
                    $players->sendMessage(TextFormat::colorize($message));
                }
            }

            $this->resetMap();
        }
    }

    public function resetMap(): void {
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new RemoveCachedBlocks($this), 1);
    }

    public function onReset(): void {
        $session = $this->getSession();

        foreach ($session->getPlayers() as $player) {
            $player->setGamemode(0);
            
            $player->setHealth(20);
            $player->setFood(20);

            $player->teleport($this->getPos($player));
        }
    }
}