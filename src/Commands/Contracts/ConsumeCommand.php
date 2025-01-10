<?php

namespace Raid\Core\Commands\Contracts;

interface ConsumeCommand
{
    public function consume(string $topic, string $event): void;

    public function getTopic(): string;

    public function getEvent(): string;

    public function getStartMessage(): string;

    public function getFinishMessage(mixed $body): string;
}
