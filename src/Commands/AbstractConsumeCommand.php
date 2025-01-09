<?php

namespace Raid\Core\Commands;

use Illuminate\Console\Command;
use Raid\Core\Traits\WithConsumeCommands;

abstract class AbstractConsumeCommand extends Command
{
    use WithConsumeCommands;
}