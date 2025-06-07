<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function findOrCreateByChatId($chatId): User
    {
        return User::firstOrCreate([
            'telegram_chat_id' => $chatId
        ], [
            'name' => 'Telegram User ' . $chatId,
            'email' => 'telegram_' . $chatId . '@example.com',
            'password' => bcrypt(bin2hex(random_bytes(8))),
        ]);
    }
}
