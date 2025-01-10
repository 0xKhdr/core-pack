<?php

namespace Raid\Core\Events;

use Raid\Core\Events\Contracts\ProduceEvent;
use Raid\Core\Traits\WithProduceEvents;

abstract class AbstractProduceEvent implements ProduceEvent
{
    use WithProduceEvents;
}
