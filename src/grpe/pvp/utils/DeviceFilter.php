<?php

declare(strict_types=1);

namespace grpe\pvp\utils;

/**
 * Class DeviceFilter
 *
 * @package grpe\pvp\utils
 * @author celis <celispost@icloud.com>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class DeviceFilter {

    /** @var string[] */
    private static $osList = [
        'Unknown',
        'Android',
        'iOS',
        'macOS',
        'FireOS',
        'GearVR',
        'HoloLens',
        'Windows 10',
        'Windows',
        'Education Edition',
        'Dedicated',
        'PlayStation',
        'Nintendo Switch',
        'Xbox'
    ];

    /** @var int[][]  */
    private static $filterList = [
        'pc' => [3, 7, 8, 10],
        'mobile' => [1, 2],
        'console' => [11, 12, 13]
    ];

    /**
     * @param string $filter
     * @param int    $osId
     *
     * @return bool
     */
    public static function isAllow(string $filter, int $osId): bool {
        return isset(self::$filterList[$filter]) and in_array($osId, self::$filterList[$filter]);
    }

    /**
     * @param int $osId
     *
     * @return string|null
     */
    public static function getOsName(int $osId): ?string {
        return self::$osList[$osId];
    }
}