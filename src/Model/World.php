<?php

declare(strict_types=1);

namespace App\Model;

final class World
{
    /* @param array<array-key, Kingdom> $kingdoms */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public array $kingdoms = [],
    ) {}
}