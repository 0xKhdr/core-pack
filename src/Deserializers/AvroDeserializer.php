<?php

namespace Raid\Core\Deserializers;

use Exception;
use AvroIOException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Message\ConsumedMessage;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Contracts\MessageDeserializer;
use FlixTech\AvroSerializer\Objects\RecordSerializer;
use FlixTech\SchemaRegistryApi\Registry\CachedRegistry;
use FlixTech\SchemaRegistryApi\Registry\PromisingRegistry;
use FlixTech\SchemaRegistryApi\Exception\SchemaRegistryException;
use FlixTech\SchemaRegistryApi\Registry\Cache\AvroObjectCacheAdapter;

class AvroDeserializer implements MessageDeserializer
{
    private RecordSerializer $recordSerializer;

    /**
     * @throws AvroIOException
     */
    public function __construct()
    {
        $httpClient             = new Client(['base_uri' => config('kafka.schema_registry')]);
        $registry               = new CachedRegistry(new PromisingRegistry($httpClient), new AvroObjectCacheAdapter());
        $this->recordSerializer = new RecordSerializer($registry);
    }

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

    private function decode(string $message): mixed
    {
        try {
            return $this->recordSerializer->decodeMessage($message);
        } catch (SchemaRegistryException|Exception $e) {
            Log::error('Failed to decode Avro message', [
                'exception' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return $message;
        }
    }
}
