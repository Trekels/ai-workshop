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

    /**
     * @return array<array-key, array{ _id: int, name: string, description: string, kingdoms: array }>
     */
    public function findAllWorlds(): array
    {
        return $this->store->findAll();
    }

    /**
     * @return array{ _id: int, name: string, description: string, kingdoms: array }
     */
    public function findWorld(int $id): array
    {
        return $this->store->findById($id);
    }
}