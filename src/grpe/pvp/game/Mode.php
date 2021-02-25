<?php

declare(strict_types=1);

namespace grpe\pvp\game;

/**
 * Class Mode
 * @package grpe\pvp\game
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class Mode {

    /**
     * @return GameSession
     */
    abstract public function getSession(): GameSession;

    /**
     * @param int $stageId
     */
    abstract public function onChangeStage(int $stageId): void;
}