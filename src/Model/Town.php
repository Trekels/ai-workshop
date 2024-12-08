<?php

declare(strict_types=1);

namespace App\Model;

final class Town
{
    /* @param array<array-key, Villager> $villagers */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public ?Kingdom $kingdom = null,
        public array $villagers = [],
    ) {}
}