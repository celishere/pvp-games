<?php

declare(strict_types=1);

namespace grpe\pvp\game\task;

use grpe\pvp\Main;

use grpe\pvp\game\mode\modes\StickDuels;

use grpe\pvp\utils\Utils;

use pocketmine\block\Block;

use pocketmine\level\Level;
use pocketmine\level\Position;

use pocketmine\scheduler\Task;

/**
 * Class RemoveCachedBlocks
 * @package grpe\pvp\game\task
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class RemoveCachedBlocks extends Task {

    private StickDuels $mode;

    private float $startTime;
    private Level $level;

    private int $i = 0;
    private int $totalCount = 0;

    private array $parts = [];

    public const MAX_BLOCK_SPLIT = 350;

    /**
     * RemoveCachedBlocks constructor.
     * @param StickDuels $mode
     */
    public function __construct(StickDuels $mode) {
        $this->level = $mode->getSession()->getLevel();

        $blocks = [];

        foreach ($mode->getCachedBlocks() as $cachedBlock => $data) {
            [$x, $y, $z] = Utils::unpackXYZ($cachedBlock);
            [$id, $meta] = $data;

            $blocks[] = Block::get($id, $meta, new Position($x, $y, $z, $this->level));
        }

        $this->splitBlocks($blocks);

        $this->mode = $mode;
        $this->startTime = microtime(true);
    }

    /**
     * @param array $blocks
     */
    private function splitBlocks(array $blocks): void {
        if (($c = count($blocks)) <= self::MAX_BLOCK_SPLIT) {
            $this->parts = [$blocks];
        } else {
            $i = 0;

            while ($c > 0) {
                $currentPart = [];

                if (($c - self::MAX_BLOCK_SPLIT) > 0) {
                    for ($a = 0; $a < self::MAX_BLOCK_SPLIT; $a++) {
                        $currentPart[] = $blocks[$i++];
                    }

                    $c -= self::MAX_BLOCK_SPLIT;
                } else {
                    for ($a = 0; $a < $c; $a++) {
                        $currentPart[] = $blocks[$i++];
                    }

                    $c = 0;
                }

                $this->parts[] = $currentPart;
            }
        }
    }

    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick): void {
        $part = $this->parts[$this->i++];

        foreach ($part as $block){
            $this->level->setBlock($block, Block::get(Block::AIR));
            $this->totalCount++;
        }

        if(!isset($this->parts[$this->i])){
            Main::getInstance()->getScheduler()->cancelTask($this->getTaskId());

            $time = round(microtime(true) - $this->startTime, 4);

            Main::getInstance()->getLogger()->info("(StickDuels) Заполнение блоков было выполнено за ". $time ."с., заполнено ". $this->totalCount ." блоков.");

            $this->mode->onReset();
        }
    }
}