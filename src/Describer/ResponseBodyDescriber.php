<?php

namespace App\Describer;

use App\Response\Dto\Interfaces\JsonPropertyProviderResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use Nelmio\ApiDocBundle\OpenApiPhp\Util;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberInterface;
use Nelmio\ApiDocBundle\RouteDescriber\RouteDescriberTrait;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\OpenApi;
use OpenApi\Annotations\Operation;
use OpenApi\Annotations\Schema;
use OpenApi\Attributes\Property;
use OpenApi\Annotations\Response as AnnotationResponse;
use OpenApi\Attributes\Response;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Routing\Route;

#[AutoconfigureTag('nelmio_api_doc.route_describer')]
class ResponseBodyDescriber implements RouteDescriberInterface
{
    public function __construct(private PropertyDescriber $propertyDescriber)
    {
    }

    const RESPONSE_FAILURE_STATUS = [
        'Bad request' => 400,
        'Unauthorized' => 401,
        'Access denied' => 403,
        'Server Error' => 500,
    ];

    use RouteDescriberTrait;
    public function describe(OpenApi $api, Route $route, \ReflectionMethod $reflectionMethod): void
    {
        if (!$classDescriptor = $this->findClass($reflectionMethod, Response::class)) {
            return;
        }

        foreach ($this->getOperations($api, $route) as $operation) {
            $this->setResponseBody($operation, $classDescriptor);
        }
    }

    private function setResponseBody(Operation $operation, ClassDescriptor $classDescriptor): void
    {
        if (is_array($operation->responses)) {
            $this->addErrorResponse($operation);
            return;
        }

        $props = $classDescriptor->responseProps + ['description' => 'success'];

        $schema = $this->getSchemaResponse($operation, $props, 200);
        $schema->properties = [
            new Property('success', type: 'boolean', example: true),
        ];

        if (!$classDescriptor->hasProperties) {
            $schema->properties[] = new Property('data', ref: new Model(type: $classDescriptor->class));
        } else {
            array_push($schema->properties, ...$classDescriptor->attributes);
        }


        $this->addErrorResponse($operation);
    }

    private function getSchemaResponse(Operation $operation, array $props, int $status)
    {
        $responseBody = Util::getIndexedCollectionItem($operation, AnnotationResponse::class, $status);

        foreach ($props as $prop => $value) {
            $responseBody->{$prop} = $value;
        }


        $responseBody->content = [];
        $responseBody->content['application/json'] = new MediaType(['mediaType' => 'application/json']);
        $schema = Util::getChild(
            $responseBody->content['application/json'],
            Schema::class
        );
        $schema->type = 'object';

        return $schema;
    }

    private function findClass(ReflectionMethod $reflectionMethod, string $class): ?ClassDescriptor
    {
        if ($this->searchAttribute($reflectionMethod->getAttributes(), $class)) {
            return null;
        }

        $type = $reflectionMethod->getReturnType();

        if ($type->isBuiltin() !== false) {
            return null;
        }

        $reflectClass = new ReflectionClass($type->getName());

        if ($attribute = $this->searchAttribute($reflectClass->getAttributes(), $class)) {
            return $this->getClassDescriptor($reflectClass, $attribute->getArguments());
        }

        return null;
    }

    private function searchAttribute(array $attributes, string $class): ?ReflectionAttribute
    {
        foreach ($attributes as $attribute) {
            if (
                $attribute->getName() === $class
                || is_subclass_of($attribute->getName(), $class)
            ) {
                return $attribute;
            }
        }

        return null;
    }

    private function addErrorResponse(Operation $operation): void
    {
        foreach (self::RESPONSE_FAILURE_STATUS as $message => $status) {
            $schema = $this->getSchemaResponse($operation, ['description' => $message], $status);
            $schema->properties = [
                new Property('success', type: 'boolean', example: false),
                new Property('message', type: 'string', example: $message)
            ];
        }
    }

    private function getClassDescriptor(ReflectionClass $class, array $classProps): ClassDescriptor
    {
        $hasProperties = $class->implementsInterface(JsonPropertyProviderResponse::class);
        $propsAttr = [];

        if ($hasProperties) {
            $propsAttr = $this->getClassProperties($class);
        }

        return new ClassDescriptor(
            $class->getName(),
            $classProps,
            $propsAttr,
            $hasProperties
        );
    }

    private function getClassProperties(ReflectionClass $class): array
    {
        $propsAttr = [];

        foreach ($class->getProperties() as $property) {
            $attr = $this->searchAttribute($property->getAttributes(), Property::class);

            if (!$attr) {
                continue;
            }

            $propsAttr[] = $this->propertyDescriber->describe($property, $attr->newInstance());
        }

        return $propsAttr;
    }
}