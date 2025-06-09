<?php

namespace App\Serializer\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class KafkaMessageSerializer implements SerializerInterface
{

    /**
     * @inheritDoc
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        return new Envelope(new SmsNotification(
            $record['id'],
            $record['name'],
            $record['description'],
        ));
    }

    /**
     * @inheritDoc
     */
    public function encode(Envelope $envelope): array
    {
        // TODO: Implement encode() method.
    }
}