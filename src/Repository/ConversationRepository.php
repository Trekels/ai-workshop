<?php

declare(strict_types=1);

namespace App\Repository;

use SleekDB\Store;

class ConversationRepository
{
    public const string NAME = 'conversations';

    public function __construct(private Store $store) {}

    /**
     * @param array{ world: int, history: array{ role: string, time: string, message: string }} $conversation
     */
    public function save(array $conversation): void
    {
        $this->store->insert($conversation);
    }

    /**
     * @return array{ world: int, history: array{ role: string, time: string, message: string }}|null
     */
    public function findForWorld(int $id): ?array
    {
        return $this->store->findOneBy(['world', '=', $id]);
    }
}