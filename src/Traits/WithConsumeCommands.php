<?php

namespace Raid\Core\Traits;

use Carbon\Exceptions\Exception;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Exceptions\ConsumerException;
use Raid\Core\Services\KafkaService;

trait WithConsumeCommands
{
    protected function init(): void
    {
        $this->info(sprintf(
            '[%s]: Init '.static::class.' with message: %s',
            now()->toDateTimeString(),
            $this->getMessage(),
        ));

    }

    protected function consume(string $topic, string $event): void
    {
        try {
            $this->init();
            $this->consumeTopic($topic, $event);

        } catch (Exception|ConsumerException $e) {
            $this->error('Error: '.$e->getMessage());
        }
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

                $this->finished(data_get($message->getBody(), 'postId'));
            });
    }

    protected function finished(string $postId): void
    {
        $this->info(sprintf(
            'Consume comment changed event received for post: %s',
            $postId,
        ));
    }
}
