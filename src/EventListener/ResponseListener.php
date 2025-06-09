<?php

namespace App\EventListener;

use App\Response\Dto\Interfaces\JsonPropertyProviderResponse;
use App\Response\Dto\Interfaces\JsonResponseInterface;
use OpenApi\Attributes\Response;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

final class ResponseListener
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }
    #[AsEventListener(event: KernelEvents::VIEW)]
    public function onKernelView(ViewEvent $event): void
    {
        $response = $event->getControllerResult();

        if (!($response instanceof JsonResponseInterface)) {
            return;
        }

        $data = $this->prepareData($response);
        $statusCode =  $this->getStatusCode($response::class);

        $jsonResponse = $this->json($data, $statusCode);

        $event->setResponse($jsonResponse);

    }

    protected function json(mixed $data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->serializer->serialize($data, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS
                | JSON_UNESCAPED_UNICODE
                | JSON_UNESCAPED_SLASHES
                | JSON_PRETTY_PRINT,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }

    private function getStatusCode(string $class): int
    {
        $reflectClass = new \ReflectionClass($class);

        foreach ($reflectClass->getAttributes() as $attribute) {
            if (
                $attribute->getName() === Response::class
                || is_subclass_of($attribute->getName(), Response::class)
            ) {
                return $attribute->getArguments()['response'] ?? 200;
            }
        }

        return 200;
    }

    private function prepareData(JsonResponseInterface $response)
    {
        $data = [
            'success' => true,
        ];

        if ($response instanceof JsonPropertyProviderResponse) {
            return array_merge($data, $response->getPropertiesData());
        }

        $data['data'] = $response;

        return $data;
    }
}
