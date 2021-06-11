<?php

declare(strict_types=1);

namespace grpe\pvp\player\sessions\task;

use pocketmine\scheduler\AsyncTask;

/**
 * Class AsyncRequestTask
 * @package grpe\pvp\player\sessions\task
 *
 * @version 1.0.0
 * @since   1.0.0
 */
class AsyncRequestTask extends AsyncTask {

    private string $username;
    private string $type;

    /**
     * AsyncRequestTask constructor.
     * @param string $username
     * @param string $type
     */
    public function __construct(string $username, string $type) {
        $this->username = $username;
        $this->type = $type;
    }

    public function onRun(): void {
    }
}