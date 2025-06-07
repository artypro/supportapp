<?php

namespace App\Dto;

use App\Models\User;

class TicketContextDto
{
    public function __construct(
        public int $chatId,
        public string $text,
        public array $state,
        public User $sender,
        public TelegramFileDto|null $fileInfo = null,
    ) {}
}
