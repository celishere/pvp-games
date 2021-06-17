<?php

declare(strict_types=1);

namespace grpe\pvp\utils;

use grpe\pvp\game\GameSession;

use grpe\pvp\game\mode\Mode;
use grpe\pvp\game\mode\BasicFFA;
use grpe\pvp\game\mode\modes\ffa\ClassicFFA;
use grpe\pvp\game\mode\modes\duels\ClassicDuels;
use grpe\pvp\game\mode\modes\duels\StickDuels;
use grpe\pvp\game\mode\modes\duels\SumoDuels;

use grpe\pvp\game\Stage;
use grpe\pvp\game\stages\CountdownStage;
use grpe\pvp\game\stages\EndingStage;
use grpe\pvp\game\stages\RunningStage;
use grpe\pvp\game\stages\WaitingStage;

use pocketmine\item\Item;
use pocketmine\math\Vector3;

use pocketmine\Player;

use pocketmine\nbt\tag\StringTag;

use InvalidArgumentException;

/**
 * Class Utils
 * @package grpe\pvp\utils
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Utils {

    /**
     * Создает дерикторию если её нет
     *
     * @param string $path
     * @param string $directory
     */
    public static function createDirectory(string $path, string $directory = ""): void{
        if(!file_exists($path . $directory)){
            @mkdir($path . $directory);
        }
    }

    /**
     * @param string $path
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
     * @param string $rawVector
     * @return Vector3
     */
    public static function unpackRawVector(string $rawVector): Vector3 {
        $loc = explode(':', $rawVector);

        if (count($loc) >= 3) {
            return new Vector3((float) $loc[0], (float) $loc[1], (float) $loc[2]);
        }

        throw new InvalidArgumentException('Неккоректная локация.');
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
     * @return array
     */
    public static function unpackXYZ(string $packedXYZ): array {
        return explode(':', $packedXYZ);
    }

    /**
     * @param int $id
     * @param GameSession $session
     * @return Stage
     */
    public static function getStageById(int $id, GameSession $session): Stage {
        switch ($id) {
            default:
            case 0:
                return new WaitingStage($session);
            case 1:
                return new CountdownStage($session);
            case 2:
                return new RunningStage($session);
            case 3:
                return new EndingStage($session);
        }
    }

    /**
     * @param string $id
     * @param GameSession $session
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
        }
    }

    /**
     * @param Item $item
     * @param string $itemName
     * @param string $tagName
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
}
