<?php

namespace grpe\pvp\game;

use grpe\pvp\Main;
use grpe\pvp\utils\Utils;

use pocketmine\Server;
use pocketmine\utils\Config;

use InvalidArgumentException;

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

                if ($mode !== "ffa") {
                    $team = $arenaData["isTeam"] ?? null;
                    if ($team === null) {
                        $logger->warning("Тип арены не указан. Имя арены - $name");
                        continue;
                    }

                    $platform = $arenaData["platform"] ?? null;
                    if ($platform === null) {
                        $logger->warning("Платформа арены не указана. Имя арены - $name");
                        continue;
                    }

                    $countdown = $arenaData["countdown"] ?? null;
                    if ($countdown === null) {
                        $logger->warning("Countdown арены не указано. Имя арены - $name.");
                        continue;
                    }

                    $minPlayers = $arenaData["minPlayers"] ?? null;
                    if ($minPlayers === null) {
                        $logger->warning("Мин. кол-во игроков арены не указано. Имя арены - $name.");
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
                        $waitingRoom = Utils::unpackRawVector($waitingRoomRaw);
                    } catch (InvalidArgumentException $e) {
                        $logger->warning("Была указана некорректная локация. Имя арены - $name.");
                        continue;
                    }
                }

                $pos1Raw = $arenaData["pos1"] ?? null;
                if($pos1Raw === null) {
                    $logger->warning("Точка #1 не указана. Имя арены - $name.");
                    continue;
                }

                try {
                    $pos1 = Utils::unpackRawVector($pos1Raw);
                } catch (InvalidArgumentException $e) {
                    $logger->warning("Была указана некорректная локация. Имя арены - $name.");
                    continue;
                }

                $pos2Raw = $arenaData["pos2"] ?? null;
                if($pos2Raw === null) {
                    $logger->warning("Точка #2 не указана. Имя арены - $name.");
                    continue;
                }

                try {
                    $pos2 = Utils::unpackRawVector($pos2Raw);
                } catch (InvalidArgumentException $e) {
                    $logger->warning("Была указана некорректная локация. Имя арены - $name.");
                    continue;
                }

                if ($mode === "ffa") {
                    $gameData = new FFAGameData($name, $mode, $world, $pos1, $pos2);
                } else {
                    $gameData = new GameData($name, $mode, $world, $team, $platform, $countdown, $maxPlayers, $minPlayers, $waitingRoom, $pos1, $pos2);
                }

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