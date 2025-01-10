<?php

namespace Raid\Core\Traits;

use Carbon\Exceptions\Exception;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Exceptions\ConsumerException;
use Raid\Core\Services\KafkaService;

trait WithConsumeCommands
{
    public function consume(string $topic, string $event): void
    {
        try {
            $this->start();
            $this->consumeTopic($topic, $event);

        } catch (Exception|ConsumerException $e) {
            $this->error('Error: '.$e->getMessage());
        }
    }

    private function start(): void
    {
        $this->info(sprintf(
            '[%s] Initiating: %s with message: %s',
            now()->toDateTimeString(),
            static::class,
            $this->getStartMessage(),
        ));
    }

    /**
     * @throws Exception|ConsumerException
     */
    private function consumeTopic(string $topic, string $event): void
    {
        KafkaService::new()->consume(
            [$topic],
            function (ConsumerMessage $message) use ($event) {
                event(new $event($message));

                $this->finish($message->getBody());
            });
    }

    private function finish(mixed $body): void
    {
        $this->info(sprintf(
            '[%s] Consumed: %s with message: %s',
            now()->toDateTimeString(),
            static::class,
            $this->getFinishMessage($body),
        ));
    }
}
