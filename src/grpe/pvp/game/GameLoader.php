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
 * @version 1.0.1
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

                $teamSpawns = [];
                $positions  = [];

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

                $platform = $arenaData['platform'] ?? null;
                if ($platform === null) {
                    $logger->warning("Платформа арены не указана. Имя арены - $name");
                    continue;
                }

                if (!Server::getInstance()->loadLevel($world)) {
                    $logger->warning("Мир не существует. Имя арены - $name.");
                    continue;
                }

                $level = Server::getInstance()->getLevelByName($world);
                $level->setAutoSave(false);

                $isFFA = $arenaData['isFFA'] ?? false;

                if (!$isFFA) {
                    $team = $arenaData["isTeam"] ?? null;
                    if ($team === null) {
                        $logger->warning("Тип арены не указан. Имя арены - $name");
                        continue;
                    }

                    $countdown = $arenaData['countdown'] ?? null;
                    if ($countdown === null) {
                        $logger->warning("Countdown арены не указано. Имя арены - $name.");
                        continue;
                    }

                    $gameTime = $arenaData['gameTime'] ?? null;
                    if ($gameTime === null) {
                        $logger->warning("GameTime арены не указано. Имя арены - $name.");
                        continue;
                    }

                    $maxPlayers = $arenaData["maxPlayers"] ?? null;
                    if ($maxPlayers === null) {
                        $logger->warning("Макс. кол-во игроков арены не указано. Имя арены - $name.");
                        continue;
                    }

                    $waitingRoomRaw = $arenaData["waitingRoom"] ?? null;
                    if ($waitingRoomRaw === null) {
                        $logger->warning("Точка ожидания не указана. Имя арены - $name.");
                        continue;
                    }

                    try {
                        $waitingRoom = Utils::unpackLocation($waitingRoomRaw, $level);
                    } catch (\Exception $e) {
                        $logger->warning("Была указана некорректная локация. Имя арены - $name.");
                        continue;
                    }

                    $spawns = $arenaData["spawns"] ?? null;
                    if ($spawns != null and !empty($spawns)) {
                        $teamSpawns = [];

                        foreach ($spawns as $teamId => $spawn) {
                            foreach ($spawn as $spawnId => $pos) {
                                try {
                                    $teamSpawns[$teamId][$spawnId] = Utils::unpackLocation($pos, $level);
                                } catch (\Exception $e) {
                                    $logger->warning("Была указана некорректная локация. Имя арены - $name.");
                                    continue;
                                }
                            }
                        }
                    } else {
                        $logger->warning("Нет точек спавна для команд. Имя арены - $name.");
                        continue;
                    }

                    if (empty($teamSpawns)) {
                        $logger->warning("Некорректные точки спавна для команд. Имя арены - $name.");
                        continue;
                    }
                } else {
                    $spawns = $arenaData['spawns'] ?? null;
                    if ($spawns != null and !empty($spawns)) {
                        $positions = [];

                        foreach ($spawns as $spawnId => $pos) {
                            try {
                                $positions[$spawnId] = Utils::unpackLocation($pos, $level);
                            } catch (\Exception $e) {
                                $logger->warning("Была указана некорректная локация. Имя арены - $name.");
                                continue;
                            }
                        }
                    }

                    if (empty($positions)) {
                        $logger->warning("Некорректные точки спавна. Имя арены - $name.");
                        continue;
                    }
                }

                if ($isFFA) {
                    $gameData = new FFAGameData($name, $mode, $world, $platform, $positions);
                } else {
                    $gameData = new GameData($name, $mode, $world, $team, $platform, $countdown, $gameTime, $maxPlayers, $waitingRoom, $teamSpawns);
                }

                $manager->addGame($gameData);
            }
        }
    }
}