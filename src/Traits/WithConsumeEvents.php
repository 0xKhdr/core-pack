<?php

namespace Raid\Core\Traits;

use Junges\Kafka\Contracts\ConsumerMessage;
use function App\Traits\data_get;

trait WithConsumeEvents
{
    public function getMessage(): ConsumerMessage
    {
        return $this->message;
    }

    public function getBody(?string $key = null, mixed $default = null): mixed
    {
        return $key
            ? data_get($this->message->getBody(), $key, $default)
            : $this->message->getBody();
    }
}
