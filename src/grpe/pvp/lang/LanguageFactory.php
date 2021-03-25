<?php

declare(strict_types=1);

namespace grpe\pvp\lang;

use grpe\pvp\Main;

/**
 * Class LanguageFactory
 * @package grpe\pvp\lang
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class LanguageFactory {

    public const DIRECTORY = "languages";
    public const FALLBACK = "ru_ru"; //дефолтный язык

    private static LanguageFactory $instance;

    private static Language $language;

    /** @var Language[] */
    private static array $languages = [];

    /** @var string[] */
    private static array $langsAvailable = [
        "en_us",
        "ru_ru"
    ];

    /**
     * LanguageFactory constructor.
     */
    public function __construct() {
        self::$instance = $this;

        foreach(self::getAvailableLanguages() as $language){
            Main::getInstance()->saveResource(self::DIRECTORY . DIRECTORY_SEPARATOR . $language . ".ini", true);

            $lang = new Language($language);
            self::$languages[$language] = $lang;
        }

        if(!in_array(Main::getInstance()->getConfig()->get("language"), self::$langsAvailable, true)){
            self::$language = new Language(self::FALLBACK);

            Main::getInstance()->getLogger()->alert(self::getDefaultLanguage()->translateString("language.not.found",
                [Main::getInstance()->getConfig()->get("language")]
            ));
        } else{
            self::$language = new Language(Main::getInstance()->getConfig()->get("language"));
        }
    }

    /**
     * @return string[]
     */
    public static function getAvailableLanguages(): array {
        return self::$langsAvailable;
    }

    /**
     * @return Language
     */
    public static function getDefaultLanguage(): Language {
        return self::$language;
    }

    /**
     * @return LanguageFactory
     */
    public static function getInstance(): LanguageFactory {
        return self::$instance;
    }

    /**
     * @param string $language
     * @return bool
     */
    public function isAvailable(string $language): bool {
        return in_array($language, self::$langsAvailable, true);
    }

    /**
     * @param string $key
     * @return Language
     */
    public function getLanguage(string $key): Language {
        return self::$languages[$key];
    }
}