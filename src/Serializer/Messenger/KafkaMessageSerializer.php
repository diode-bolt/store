<?php

namespace App\Serializer\Messenger;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializer;

class KafkaMessageSerializer implements SerializerInterface
{
    public function __construct(private readonly SymfonySerializer $serializer)
    {
    }

    /**
     * @inheritDoc
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $object = $this->serializer->decode($encodedEnvelope['data'], $encodedEnvelope['headers']['type']);

        if (!$object) {
            throw new \Error();
        }

        return new Envelope($object);
    }

    /**
     * @inheritDoc
     */
    public function encode(Envelope $envelope): array
    {
        $object = $envelope->getMessage();

        return [
            'body' => $this->serializer->serialize($object, 'json'),
            'headers' => [
                'type' => $object::class
            ]
        ];
    }
}