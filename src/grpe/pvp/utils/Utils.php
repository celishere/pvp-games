<?php

declare(strict_types=1);

namespace grpe\pvp\utils;

use Exception;
use ReflectionException;

use grpe\pvp\game\GameSession;

use grpe\pvp\game\mode\Mode;
use grpe\pvp\game\mode\BasicFFA;
use grpe\pvp\game\mode\ffa\FistFFA;
use grpe\pvp\game\mode\ffa\GappleFFA;
use grpe\pvp\game\mode\ffa\ClassicFFA;
use grpe\pvp\game\mode\ffa\NodebuffFFA;
use grpe\pvp\game\mode\ffa\ResistanceFFA;
use grpe\pvp\game\mode\duels\ClassicDuels;
use grpe\pvp\game\mode\duels\StickDuels;
use grpe\pvp\game\mode\duels\SumoDuels;

use grpe\pvp\game\Stage;
use grpe\pvp\game\stages\CountdownStage;
use grpe\pvp\game\stages\EndingStage;
use grpe\pvp\game\stages\RunningStage;
use grpe\pvp\game\stages\WaitingStage;

use pocketmine\Player;

use pocketmine\item\Item;

use pocketmine\level\Level;
use pocketmine\level\Location;

use pocketmine\nbt\tag\StringTag;

/**
 * Class Utils
 * @package grpe\pvp\utils
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.1
 * @since   1.0.0
 */
class Utils {

    /**
     * Создает директорию если её нет
     *
     * @param string $path
     * @param string $directory
     */
    public static function createDirectory(string $path, string $directory = ""): void {
        if (!file_exists($path . $directory)) {
            @mkdir($path . $directory);
        }
    }

    /**
     * @param string $path
     *
     * @return array
     */
    public static function getArenaFiles(string $path): array {
        $files = [];

        foreach (scandir($path) as $file) {
            if (stripos($file, '.json')) {
                $files[] = $file;
            }
        }

        return $files;
    }

    /**
     * @param string $pos
     * @param Level  $level
     *
     * @return Location
     * @throws Exception
     */
    public static function unpackLocation(string $pos, Level $level): Location {
        $data = explode('_', $pos);

        if (count($data) < 5) {
            throw new ReflectionException('Недостаточно данных.');
        }

        return new Location((float) $data[0], (float) $data[1], (float) $data[2], (float) $data[3], (float) $data[4], $level);
    }

    /**
     * @param int $x
     * @param int $y
     * @param int $z
     *
     * @return string
     */
    public static function packXYZ(int $x, int $y, int $z): string {
        return $x .':'. $y .':'. $z;
    }

    /**
     * @param string $packedXYZ
     *
     * @return array
     */
    public static function unpackXYZ(string $packedXYZ): array {
        return explode(':', $packedXYZ);
    }

    /**
     * @param int $id
     * @param GameSession $session
     *
     * @return Stage
     */
    public static function getStageById(int $id, GameSession $session): Stage {
        switch ($id) {
            default:
            case Stage::WAITING:
                return new WaitingStage($session);
            case Stage::COUNTDOWN:
                return new CountdownStage($session);
            case Stage::RUNNING:
                return new RunningStage($session);
            case Stage::ENDING:
                return new EndingStage($session);
        }
    }

    /**
     * @param string $id
     * @param GameSession $session
     *
     * @return Mode|BasicFFA
     */
    public static function getModeById(string $id, GameSession $session) {
        switch ($id) {
            default:
            case 'stick':
                return new StickDuels($session);
            case 'classic':
                return new ClassicDuels($session);
            case 'sumo':
                return new SumoDuels($session);
            case 'ffa':
                return new ClassicFFA($session);
            case 'nodebuff':
                return new NodebuffFFA($session);
            case 'resistance':
                return new ResistanceFFA($session);
            case 'fist':
                return new FistFFA($session);
            case 'gapple':
                return new GappleFFA($session);
        }
    }

    /**
     * @param Item $item
     * @param string $itemName
     * @param string $tagName
     *
     * @return Item
     */
    public static function createNamedTagItem(Item $item, string $itemName, string $tagName): Item {
        $item->setCustomName($itemName);
        $item->setNamedTagEntry(new StringTag($tagName));

        return $item;
    }

    /**
     * @param Player $player
     */
    public static function reset(Player $player): void {
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll(); //в 1.1 такого нету

        $player->setGamemode(2);
        $player->setHealth(20);
        $player->setMaxHealth(20);
        $player->setFood(20);
        $player->setXpLevel(0);
        $player->setXpProgress(0);
    }

    /**
     * @param int $time
     *
     * @return string
     */
    public static function convertTime(int $time): string {
        $min = date('i', $time);
        $sec = date('s', $time);

        if ($min[0] === '0') {
            $min = substr($min, 1);
        }

        return "$min:$sec";
    }
}
