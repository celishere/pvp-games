<?php

namespace grpe\pvp\game;

use grpe\pvp\Main;
use grpe\pvp\utils\Utils;

use pocketmine\Server;
use pocketmine\utils\Config;

/**
 * Class GameLoader
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
final class GameLoader {

    public static function loadArenas(): void {
        $path = Main::getInstance()->getDataFolder() . 'arenas/';
        $logger = Main::getInstance()->getLogger();
        $manager = Main::getGameManager();

        foreach (Utils::getArenaFiles($path) as $file) {
            $config = new Config($path . $file);

            if ($config->check()) {
                $arenaData = $config->getAll();

                $name = $arenaData["name"] ?? null;
                if($name === null) {
                    $logger->warning("Нет названия арены. Файл: ". $file);
                    continue;
                }

                $mode = $arenaData["mode"] ?? null;
                if($mode === null) {
                    $logger->warning("Режим арены не указан. Имя арены - $name");
                    continue;
                }

                $world = $arenaData["world"] ?? null;
                if($world === null) {
                    $logger->warning("Мир арены не указан. Имя арены - $name");
                    continue;
                }

                $team = $arenaData["isTeam"] ?? null;
                if($team === null) {
                    $logger->warning("Тип арены не указан. Имя арены - $name");
                    continue;
                }

                $countdown = $arenaData["countdown"] ?? null;
                if($countdown === null) {
                    $logger->warning("Countdown арены не указано. Имя арены - $name.");
                    continue;
                }

                $minPlayers = $arenaData["min"] ?? null;
                if($minPlayers === null) {
                    $logger->warning("Мин. кол-во игроков арены не указано. Имя арены - $name.");
                    continue;
                }

                $maxPlayers = $arenaData["max"] ?? null;
                if($maxPlayers === null) {
                    $logger->warning("Макс. кол-во игроков арены не указано. Имя арены - $name.");
                    continue;
                }

                $gameData = new GameData($name, $mode, $world, $team, $countdown, $maxPlayers, $minPlayers);

                if (!Server::getInstance()->loadLevel($world)) {
                    $logger->warning("Мир не существует. Имя арены - $name.");
                    continue;
                }

                Server::getInstance()->getLevelByName($world)->setAutoSave(false);

                $manager->addGame($gameData);
            }
        }
    }
}