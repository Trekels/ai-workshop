<?php

declare(strict_types=1);

namespace App\Model;

final class Villager
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public ?Town $town = null,
    ) {}
}