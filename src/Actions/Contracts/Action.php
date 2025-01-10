<?php

namespace Raid\Core\Actions\Contracts;

use Throwable;

interface Action
{
    /**
     * Execute the action with static call.
     */
    public static function exec(...$arguments): mixed;

    /**
     * Execute the action.
     */
    public function execute(...$arguments): mixed;

    /**
     * Log the exception.
     */
    public function log(Throwable $throwable): void;
}
