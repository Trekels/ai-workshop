<?php

declare(strict_types=1);

namespace App\Model;

final class Kingdom
{
    /* @param array<array-key, Town> $towns */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public ?World $world = null,
        public array $towns = [],
    ) {}
}