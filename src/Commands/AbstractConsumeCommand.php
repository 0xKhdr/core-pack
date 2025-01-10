<?php

namespace Raid\Core\Commands;

use Illuminate\Console\Command;
use Raid\Core\Traits\WithConsumeCommands;

abstract class AbstractConsumeCommand extends Command
{
    use WithConsumeCommands;

    abstract public function getTopic(): string;

    abstract public function getEvent(): string;

    abstract public function getStartMessage(): string;

    abstract public function getFinishMessage(mixed $body): string;

    public function handle(): void
    {
        $this->consume($this->getTopic(), $this->getEvent());
    }
}
