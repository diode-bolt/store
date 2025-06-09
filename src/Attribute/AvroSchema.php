<?php

namespace App\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AvroSchema
{
    public function __construct(
        public string $schemaName,
        public string $namespace = 'app',
        public string $doc = ''
    ) {
    }
}