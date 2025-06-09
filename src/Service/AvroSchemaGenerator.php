<?php

namespace App\Service;

use App\Attribute\AvroSchema;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;

class AvroSchemaGenerator
{
    private array $schemas = [];
    private array $processedClasses = [];

    public function __construct(
        #[Autowire('%kernel.project_dir%')] private string $projectDir
    ) {
    }

    public function generateSchemas(): array
    {
        $this->schemas = [];
        $this->processedClasses = [];

        $srcDir = $this->projectDir.'/src';

        $finder = new Finder();
        $finder->files()->in($srcDir)->name('*.php');

        foreach ($finder as $file) {
            $className = $this->getClassNameFromFile($file->getPathname());

            if ($className && class_exists($className)) {
                $this->processClass($className);
            }
        }

        return $this->schemas;
    }

    /**
     * @throws \ReflectionException
     */
    private function processClass(string $className): void
    {
        if (in_array($className, $this->processedClasses)) {
            return;
        }

        $reflection = new \ReflectionClass($className);
        $attributes = $reflection->getAttributes(AvroSchema::class);

        if (empty($attributes)) {
            return;
        }

        /** @var AvroSchema $avroAttribute */
        $avroAttribute = $attributes[0]->newInstance();

        $schemaName = $avroAttribute->schemaName ?: $reflection->getShortName();
        $namespace = $avroAttribute->namespace ?: 'app';

        $schema = [
            'type' => 'record',
            'name' => $schemaName,
            'namespace' => $namespace,
            'doc' => $avroAttribute->doc,
            'fields' => [],
        ];

        foreach ($reflection->getProperties() as $property) {
            $fieldSchema = $this->processProperty($property);
            if ($fieldSchema) {
                $schema['fields'][] = $fieldSchema;
            }
        }

        $this->schemas[$schemaName] = $schema;
        $this->processedClasses[] = $className;
    }

    /**
     * @throws \ReflectionException
     */
    private function processProperty(\ReflectionProperty $property): ?array
    {
        $propertyAttributes = $property->getAttributes(AvroSchema::class);
        $avroAttribute = !empty($propertyAttributes) ? $propertyAttributes[0]->newInstance() : null;

        // Если у свойства указан явный тип в атрибуте
        if ($avroAttribute && $avroAttribute->type) {
            return [
                'name' => $property->getName(),
                'type' => $avroAttribute->type,
                'doc' => $avroAttribute->doc,
            ];
        }

        $type = $property->getType();
        $typeName = $type?->getName();

        // Обработка вложенных объектов
        if ($typeName && class_exists($typeName)) {
            $this->processClass($typeName);
            $nestedClassReflection = new \ReflectionClass($typeName);
            $nestedAttributes = $nestedClassReflection->getAttributes(AvroSchema::class);

            if (!empty($nestedAttributes)) {
                $nestedAvroAttribute = $nestedAttributes[0]->newInstance();
                $nestedSchemaName = $nestedAvroAttribute->schemaName ?: $nestedClassReflection->getShortName();

                return [
                    'name' => $property->getName(),
                    'type' => $nestedSchemaName,
                    'doc' => $avroAttribute->doc ?? '',
                ];
            }
        }

        // Обработка массивов объектов
        if ($typeName === 'array') {
            $docComment = $property->getDocComment();
            if ($docComment && preg_match('/@var\s+([^\s]+)\[]/', $docComment, $matches)) {
                $itemType = $matches[1];
                if (class_exists($itemType)) {
                    $this->processClass($itemType);
                    $itemClassReflection = new \ReflectionClass($itemType);
                    $itemAttributes = $itemClassReflection->getAttributes(AvroSchema::class);

                    if (!empty($itemAttributes)) {
                        $itemAvroAttribute = $itemAttributes[0]->newInstance();
                        $itemSchemaName = $itemAvroAttribute->schemaName ?: $itemClassReflection->getShortName();

                        return [
                            'name' => $property->getName(),
                            'type' => [
                                'type' => 'array',
                                'items' => $itemSchemaName
                            ],
                            'doc' => $avroAttribute->doc ?? '',
                        ];
                    }
                }
            }
        }

        // Базовые типы
        $avroType = $this->mapPhpTypeToAvro($type);
        return [
            'name' => $property->getName(),
            'type' => $avroType,
            'doc' => $avroAttribute->doc ?? '',
        ];
    }

    private function mapPhpTypeToAvro(?\ReflectionType $type): string|array
    {
        if (!$type) {
            return 'null';
        }

        $typeName = $type->getName();

        return match ($typeName) {
            'int' => 'int',
            'float' => 'double',
            'string' => 'string',
            'bool' => 'boolean',
            'array' => ['type' => 'array', 'items' => 'string'], // по умолчанию
            default => 'string'
        };
    }

    private function getClassNameFromFile(string $path): ?string
    {
        $content = file_get_contents($path);
        $tokens = token_get_all($content);
        $namespace = '';
        $class = null;

        for ($i = 0; isset($tokens[$i]); $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; isset($tokens[$j]); $j++) {
                    if ($tokens[$j][0] === T_STRING) {
                        $namespace .= '\\'.$tokens[$j][1];
                    } elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
                        break;
                    }
                }
            }

            if ($tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; isset($tokens[$j]); $j++) {
                    if ($tokens[$j] === '{') {
                        $class = $tokens[$i + 2][1];
                        break 2;
                    }
                }
            }
        }

        return $class ? $namespace.'\\'.$class : null;
    }
}