<?php

declare(strict_types=1);

namespace grpe\pvp\game\mode\modes\duels;

use grpe\pvp\game\GameSession;
use grpe\pvp\game\mode\BasicDuels;

/**
 * Class SumoDuels
 * @package grpe\pvp\game\mode\modes\duels
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