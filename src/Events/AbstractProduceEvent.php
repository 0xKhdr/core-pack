<?php

namespace Raid\Core\Events;

use Junges\Kafka\Contracts\ConsumerMessage;
use Raid\Core\Events\Contracts\ConsumeEvent;
use Raid\Core\Events\Contracts\ProduceEvent;
use Raid\Core\Traits\WithConsumeEvents;
use Raid\Core\Traits\WithProduceEvents;

abstract class AbstractProduceEvent implements ProduceEvent
{
    use WithProduceEvents;
}