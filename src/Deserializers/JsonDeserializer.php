<?php

namespace Raid\Core\Deserializers;

use Exception;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Message\ConsumedMessage;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageDeserializer;

class JsonDeserializer implements MessageDeserializer
{
    public function deserialize(ConsumerMessage $message): ConsumedMessage
    {
        return new ConsumedMessage(
            topicName: $message->getTopicName(),
            partition: $message->getPartition(),
            headers  : $message->getHeaders(),
            body     : $this->decode($message->getBody()),
            key      : $message->getKey(),
            offset   : $message->getOffset(),
            timestamp: $message->getTimestamp()
        );
    }

    private function decode(?string $message): mixed
    {
        try {
            return json_decode($message, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            Log::error('Failed to decode JSON message', [
                'exception' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return $message;
        }
    }
}
