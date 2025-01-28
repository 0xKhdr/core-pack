<?php

namespace Raid\Core\Consumers;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\ConsumerMessage;
use Raid\Core\Consumers\Contracts\ChangeConsumer;
use Raid\Core\Enums\KafkaChange;
use Throwable;

abstract class AbstractChangeConsumer implements ChangeConsumer
{
    protected readonly string $operation;

    protected readonly ?array $before;

    protected readonly ?array $after;

    abstract protected function create(): void;

    abstract protected function update(): void;

    abstract protected function delete(): void;

    public function resolveChange(ConsumerMessage $message): void
    {
        try {
            $this->resolveMessage($message);
            $this->handleChange();
        } catch (Exception $e) {
            $this->log($e, $message);
        }
    }

    private function resolveMessage(ConsumerMessage $message): void
    {
        $this->operation = data_get($message->getBody(), 'op');
        $this->before = data_get($message->getBody(), 'before');
        $this->after = data_get($message->getBody(), 'after');
    }

    private function handleChange(): void
    {
        match ($this->getOperation()) {
            KafkaChange::CREATE => $this->create(),
            KafkaChange::UPDATE => $this->update(),
            KafkaChange::DELETE => $this->delete(),
        };
    }

    private function log(Throwable $throwable, ConsumerMessage $message): void
    {
        $message = sprintf('Failed to consume %s change from %s table',
            data_get($message->getBody(), 'op'),
            data_get($message->getBody(), 'source.table')
        );

        Log::error($message, [
            'exception' => $throwable->getMessage(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
        ]);
    }

    protected function getId(): string
    {
        return $this->getAfter('id') ?: $this->getBefore('id');
    }

    public function getOperation(): string
    {
        return $this->operation;
    }

    public function getBefore(?string $key = null, mixed $default = null): mixed
    {
        return $key
            ? Arr::get($this->before, $key, $default)
            : $this->before;
    }

    public function getAfter(?string $key = null, mixed $default = null): mixed
    {
        return $key
            ? Arr::get($this->after, $key, $default)
            : $this->after;
    }
}
