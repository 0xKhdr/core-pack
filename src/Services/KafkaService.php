<?php

namespace Raid\Core\Services;

use Carbon\Exceptions\Exception as CarbonException;
use Exception;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaService
{
    public static function new(): static
    {
        return app(static::class);
    }

    /**
     * @throws Exception
     */
    public function produce(string $topic, array $body, array $headers = [], ?string $key = null): void
    {
        Kafka::publish(config('kafka.brokers'))
            ->onTopic($topic)
            ->withMessage(new Message(
                headers: $headers,
                body: $body,
                key: $key,
            ))
            ->send();
    }

    /**
     * @throws CarbonException|ConsumerException
     */
    public function consume(array $topics, $handler): void
    {
        Kafka::consumer($topics)
            ->withBrokers(config('kafka.brokers'))
            ->withAutoCommit()
            ->withHandler($handler)
            ->build()
            ->consume();
    }
}
