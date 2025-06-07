<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\TicketMessage;
use App\Models\User;
use App\Notifications\TicketSubmittedSlack;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Telegram\Bot\Api;

class TelegramTicketService
{
    public function __construct(protected Api $telegram, protected SlackTicketNotifier $notifier, protected UserService $userService)
    {
    }

    public function handleUpdate($update)
    {
        if ($update->has('message')) {
            $chatId = $update->getMessage()->getChat()->getId();
            $text   = $update->getMessage()->getText();

            $state = Cache::get("tg_ticket_state_{$chatId}");
            if ($state && isset($state['step'])) {
                if ($state['step'] === 'subject') {
                    $state['subject'] = mb_substr($text, 0, 2000);
                    $state['step'] = 'message';
                    Cache::put("tg_ticket_state_{$chatId}", $state, now()->addMinutes(30));
                    $this->sendMessage($chatId, "Please provide the message body for your ticket (max 2000 chars).");
                    return true;
                }

                if ($state['step'] === 'message') {
                    $state['message'] = mb_substr($text, 0, 2000);
                    // Use TelegramUserService to get or create the sender
                    $sender = $this->userService->findOrCreateByChatId($chatId);

                    $ticket = Ticket::create([
                        'user_id'     => $sender->id,
                        'channel'     => Ticket::CHANNEL_TLGM,
                        'category_id' => $state['category_id'],
                        'subject'     => $state['subject'],
                        'status'      => Ticket::STATUS_NEW,
                    ]);

                    TicketMessage::create([
                        'ticket_id' => $ticket->id,
                        'sender_id' => $sender->id,
                        'text'      => $state['message'],
                    ]);

                    // Send Slack notification
                    $this->notifier->notify($ticket);

                    $ticketNumber = str_pad($ticket->id, 5, '0', STR_PAD_LEFT);
                    $this->sendMessage($chatId, "Ticket with ID {$ticketNumber} created.");
                    Cache::forget("tg_ticket_state_{$chatId}");
                    return true;
                }
            }
            if ($text === '/start' || strtolower($text) === 'hello') {
                $this->sendGreeting($chatId);
                return true;
            }
        }
        if ($update->has('callback_query')) {
            $this->handleCallbackQuery($update->getCallbackQuery());
            return true;
        }
        return false;
    }

    public function sendGreeting($chatId)
    {
        $categories = Category::all();
        $keyboard   = [];
        foreach ($categories as $category) {
            $keyboard[] = [['text' => $category->name, 'callback_data' => $category->slug]];
        }
        $replyMarkup = ['inline_keyboard' => $keyboard];
        $this->sendMessage($chatId, 'Welcome! Please select a category:', $replyMarkup);
    }

    public function handleCallbackQuery($callbackQuery)
    {
        $chatId       = $callbackQuery->getMessage()->getChat()->getId();
        $categorySlug = $callbackQuery->getData();
        $category = Category::where('slug', $categorySlug)->first();
        if ($category) {
            Cache::put("tg_ticket_state_{$chatId}", [
                'step'        => 'subject',
                'category_id' => $category->id,
            ], now()->addMinutes(30));
            $this->sendMessage($chatId, "You selected: {$category->name}. Please provide a subject for your ticket (max 2000 chars).");
        }
    }

    public function sendMessage($chatId, $text, $replyMarkup = null)
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
}
