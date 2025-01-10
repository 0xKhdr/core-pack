<?php

namespace Raid\Core\Commands;

use Illuminate\Console\Command;
use Raid\Core\Commands\Contracts\ConsumeCommand;
use Raid\Core\Traits\WithConsumeCommands;

abstract class AbstractConsumeCommand extends Command implements ConsumeCommand
{
    use WithConsumeCommands;

    public function handle(): void
    {
        $this->consume($this->getTopic(), $this->getEvent());
    }
}
