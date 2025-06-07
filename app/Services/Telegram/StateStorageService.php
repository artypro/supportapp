<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Cache;

class StateStorageService
{
    private int $ttlMinutes;

    public function __construct(int $ttlMinutes = 30)
    {
        $this->ttlMinutes = $ttlMinutes;
    }

    public function get(int|string $chatId): ?array
    {
        return Cache::get($this->key($chatId));
    }

    public function set(int|string $chatId, array $state): void
    {
        Cache::put($this->key($chatId), $state, now()->addMinutes($this->ttlMinutes));
    }

    public function clear(int|string $chatId): void
    {
        Cache::forget($this->key($chatId));
    }

    private function key(int|string $chatId): string
    {
        return "tg_ticket_state_{$chatId}";
    }
}
