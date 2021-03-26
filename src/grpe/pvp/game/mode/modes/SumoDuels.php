<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes;

use grpe\pvp\game\mode\BasicDuels;
use grpe\pvp\game\GameSession;

/**
 * Class SumoDuels
 * @package grpe\pvp\game\mode
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class SumoDuels extends BasicDuels {

    /**
     * SumoDuels constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        parent::__construct($session);
    }
}