<?php

namespace Raid\Core\Events;

use Junges\Kafka\Contracts\ConsumerMessage;
use Raid\Core\Events\Contracts\ConsumeEvent;
use Raid\Core\Traits\WithConsumeEvents;

abstract class AbstractConsumeEvent implements ConsumeEvent
{
    use WithConsumeEvents;

    public function __construct(
        private ConsumerMessage $message,
    ) {}
}