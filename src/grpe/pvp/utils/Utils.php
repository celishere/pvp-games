<?php

declare(strict_types=1);

namespace grpe\pvp\utils;

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
}