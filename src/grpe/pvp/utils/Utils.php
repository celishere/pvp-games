<?php

declare(strict_types=1);

namespace grpe\pvp\utils;

use grpe\pvp\Main;
use InvalidArgumentException;
use JsonException;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\Server;

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
        // $loc = explode('_', $rawVector);
        $loc = explode(':', $rawVector);

        if (count($loc) >= 3) {
            return new Location((float) $loc[0], (float) $loc[1], (float) $loc[2]);
        }

        throw new InvalidArgumentException('Неккоректная локация.');
    }
}
