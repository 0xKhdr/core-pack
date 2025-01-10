<?php

namespace Raid\Core\Events\Contracts;

use Junges\Kafka\Contracts\ConsumerMessage;

interface ConsumeEvent
{
    public function getMessage(): ConsumerMessage;

    public function getBody(?string $key = null, mixed $default = null): mixed;
}
