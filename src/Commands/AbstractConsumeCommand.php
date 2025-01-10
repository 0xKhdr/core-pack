<?php

namespace Raid\Core\Commands;

use Illuminate\Console\Command;
use Raid\Core\Traits\WithConsumeCommands;

abstract class AbstractConsumeCommand extends Command
{
    use WithConsumeCommands;

    abstract public function getMessage(): string;

    abstract public function getTopic(): string;

    abstract public function getEvent(): string;

    public function handle(): void
    {
        $this->consume($this->getTopic(), $this->getEvent());
    }
}
