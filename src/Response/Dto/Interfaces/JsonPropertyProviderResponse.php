<?php

namespace App\Response\Dto\Interfaces;


interface JsonPropertyProviderResponse extends JsonResponseInterface
{
    public function getPropertiesData(): array;
}