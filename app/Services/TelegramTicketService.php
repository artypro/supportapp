<?php

namespace App\Services;

use App\Dto\TelegramFileDto;
use App\Exceptions\TelegramFileTooLargeException;
use App\Models\Category;
use App\Services\Telegram\States\SubjectState;
use App\Services\Telegram\States\MessageState;
use App\Dto\TicketContextDto;
use Telegram\Bot\Objects\Document;
use App\Services\Telegram\StateStorageService;
use Telegram\Bot\Objects\Message;
use App\Services\Telegram\TelegramApiService;

class TelegramTicketService
{
    const MAX_FILE_SIZE        = 5 * 1024 * 1024; // 5MB
    const STATE_STEP_SUBJECT   = 'subject';
    const STATE_STEP_MESSAGE   = 'message';
    const START_DIALOGUE_WORDS = ['/start', 'hello'];

    public function __construct(
        protected UserService $userService,
        protected TelegramFileService $telegramFileService,
        protected TicketNumberService $ticketNumberService,
        protected MessageBuilder $messageBuilder,
        protected StateStorageService $stateStorage,
        protected TelegramApiService $telegramApiService
    ) {}

    public function handleUpdate($update): bool
    {
        if ($update->has('message')) {
            return $this->handleMessageUpdate($update->getMessage());
        }

        if ($update->has('callback_query')) {
            return $this->handleCallbackQueryUpdate($update->getCallbackQuery());
        }

        return false;
    }

    private function buildContext(int $chatId, string $text, array $state, TelegramFileDto|null $fileInfo): TicketContextDto
    {
        return new TicketContextDto(
            chatId: $chatId,
            text: $text,
            state: $state,
            sender: $this->userService->findOrCreateByChatId($chatId),
            fileInfo: $fileInfo,
        );
    }

    private function handleMessageUpdate(Message $message): bool
    {
        $chatId = $message->getChat()->getId();
        $messageText   = $message->getText();
        $document = $message->getDocument();

        if ($document) {
            $messageText = $document->caption;
        }

        try {
            $fileInfo = $this->getFileInfo($document);
        } catch (TelegramFileTooLargeException $e) {
            $this->telegramApiService->sendMessage($chatId, $this->messageBuilder->get(MessageBuilder::FILE_TOO_LARGE));
            return true;
        }

        $state = $this->stateStorage->get($chatId);
        if ($state && isset($state['step'])) {
            $context = $this->buildContext($chatId, $messageText, $state, $fileInfo);
            if ($state['step'] === self::STATE_STEP_SUBJECT) {
                return (new SubjectState())->handle($message, $context);
            }
            if ($state['step'] === self::STATE_STEP_MESSAGE) {
                return (new MessageState())->handle($message, $context);
            }
        }

        if (in_array(strtolower($messageText), self::START_DIALOGUE_WORDS)) {
            $this->telegramApiService->sendGreeting($chatId);
            return true;
        }

        return false;
    }

    private function handleCallbackQueryUpdate($callbackQuery): bool
    {
        $chatId       = $callbackQuery->getMessage()->getChat()->getId();
        $categorySlug = $callbackQuery->getData();
        $category = Category::where('slug', $categorySlug)->first();

        if ($category) {
            $this->stateStorage->set($chatId, [
                'step'        => 'subject',
                'category_id' => $category->id,
            ]);

            $this->telegramApiService->sendMessage($chatId, "You selected: {$category->name}. " . $this->messageBuilder->get(MessageBuilder::GREETING));

            return true;
        }
        return false;
    }

    private function getFileInfo(Document|null $document): TelegramFileDto|null
    {
        if ($document) {
            return $this->telegramFileService->processDocument($document, self::MAX_FILE_SIZE);
        }

        return null;
    }
}
