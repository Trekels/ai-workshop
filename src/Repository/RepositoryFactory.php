<?php

declare(strict_types=1);

namespace App\Repository;

use SleekDB\Query;
use SleekDB\Store;

readonly class RepositoryFactory
{
    public function __construct(private string $path) {}

    public function create(string $name): Store
    {
        return new Store($name, $this->path, [
            'timeout' => false,
            'auto_cache' => true,
            'primary_key' => '_id',
            'cache_lifetime' => null,
            'search' => [
                'min_length' => 2,
                'mode' => 'or',
                'score_key' => 'scoreKey',
                'algorithm' => Query::SEARCH_ALGORITHM['hits']
            ],
            'folder_permissions' => 0777,
        ]);
    }
}