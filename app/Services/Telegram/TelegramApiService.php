<?php

namespace App\Services\Telegram;

use App\Models\Category;
use App\Services\MessageBuilder;
use Telegram\Bot\Api;

class TelegramApiService
{
    public function __construct(
        protected Api $telegram,
        protected MessageBuilder $messageBuilder,
    ) {}

    public function sendDefinedMessage(int $chatId, string $constText): void {
        $text = $this->messageBuilder->get($constText);

        $this->sendMessage($chatId, $text);
    }

    public function sendMessage(int $chatId, string $text, array $replyMarkup = null): void
    {
        $params = [
            'chat_id' => $chatId,
            'text'    => $text,
        ];
        if ($replyMarkup) {
            $params['reply_markup'] = json_encode($replyMarkup);
        }
        $this->telegram->sendMessage($params);
    }

    public function sendGreeting(int $chatId): void
    {
        $categories = Category::all();
        $keyboard   = [];
        foreach ($categories as $category) {
            $keyboard[] = [['text' => $category->name, 'callback_data' => $category->slug]];
        }
        $replyMarkup = ['inline_keyboard' => $keyboard];

        $this->sendMessage($chatId, $this->messageBuilder->get(MessageBuilder::GREETING), $replyMarkup);
    }
}
