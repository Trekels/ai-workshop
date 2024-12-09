<?php

declare(strict_types=1);

namespace App\Repository;

use SleekDB\Store;

class WorldRepository
{
    public const string NAME = 'worlds';

    public function __construct(private Store $store) {}

    /**
     * @param array{ name: string, description: string, kingdoms: array } $world
     */
    public function save(array $world): void
    {
        $this->store->insert($world);
    }
}