<?php

declare(strict_types=1);

namespace grpe\pvp\utils;

use pocketmine\utils\TextFormat;

/**
 * Class TeamData
 *
 * @package grpe\pvp\utils
 * @author celis <celispost@icloud.com>
 *
 * @version 1.0.2
 * @since   1.0.2
 */
class TeamData {

    public const RED = 1;
    public const GREEN = 2;

    /** @var string[] */
    public const COLORS = [
        self::RED => TextFormat::RED,
        self::GREEN => TextFormat::GREEN
    ];

    /** @var string[] */
    public const NAMES = [
        self::RED => "Красные",
        self::GREEN => "Зеленые"
    ];
}