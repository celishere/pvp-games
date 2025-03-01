<?php

declare(strict_types=1);

namespace grpe\pvp\game;

/**
 * Class Stage
 * @package grpe\pvp\game\stage
 *
 * @author celis <celishere@gmail.com> <Telegram:@celishere>
 *
 * @version 1.0.0
 * @since   1.0.0
 */
abstract class Stage {

    public const WAITING = 1;
    public const COUNTDOWN = 2;
    public const RUNNING = 3;
    public const ENDING = 4;

    private GameSession $session;

    private int $time;

    /**
     * Stage constructor.
     * @param GameSession $session
     */
    public function __construct(GameSession $session) {
        $this->session = $session;
    }

    /**
     * @return GameSession
     */
    public function getSession(): GameSession {
        return $this->session;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void {
        $this->time = $time;
    }

    /**
     * @return int
     */
    public function getTime(): int {
        return $this->time;
    }

    /**
     * @return int
     */
    abstract public function getId(): int;

    abstract public function onTick(): void;
}