<?php

namespace Raid\Core\Consumers\Contracts;

use Junges\Kafka\Contracts\ConsumerMessage;

interface ChangeConsumer
{
    public function resolveChange(ConsumerMessage $message): void;

    public function getOperation(): string;

    public function getBefore(?string $key = null, mixed $default = null): mixed;

    public function getAfter(?string $key = null, mixed $default = null): mixed;
}
