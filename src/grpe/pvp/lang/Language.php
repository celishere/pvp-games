<?php

declare(strict_types=1);

namespace grpe\pvp\lang;

use grpe\pvp\Main;

use pocketmine\lang\BaseLang;

use pocketmine\utils\TextFormat;

/**
 * Class Language
 * @package grpe\pvp\lang
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class Language extends BaseLang {

    /**
     * Language constructor.
     * @param string $lang
     */
    public function __construct(string $lang) {
        $path = Main::getInstance()->getDataFolder() . LanguageFactory::DIRECTORY . DIRECTORY_SEPARATOR;

        parent::__construct($lang, $path, LanguageFactory::FALLBACK);
    }

    /**
     * @param string $str
     * @param array $params
     * @param string|null $onlyPrefix
     *
     * @return string
     */
    public function translateString(string $str, array $params = [], string $onlyPrefix = null): string {
        return TextFormat::colorize(parent::translateString($str, $params, $onlyPrefix));
    }
}