<?php

namespace App\Services;

use Telegram\Bot\Api;
use App\Exceptions\TelegramFileTooLargeException;
use Telegram\Bot\Objects\Document;
use App\Dto\TelegramFileDto;
use Illuminate\Support\Facades\Storage;

class TelegramFileService
{
    const TELEGRAM_FILE_API_URL = 'https://api.telegram.org/file/bot';

    public function __construct(protected Api $telegram) {}

    protected function buildFileUrl(string $filePath): string
    {
        return self::TELEGRAM_FILE_API_URL . config('services.telegram.bot_token') . "/$filePath";
    }

    private function downloadAndStoreFile(string $fileUrl, string $fileName): string
    {
        $fileContents = file_get_contents($fileUrl);
        $storagePath = 'tickets/' . uniqid() . '_' . ($fileName ?: 'file');
        Storage::disk('local')->put($storagePath, $fileContents);

        return $storagePath;
    }

    public function processDocument(Document $document, int $maxSize = 5242880): TelegramFileDto
    {
        $fileId = $document->getFileId();
        $fileSize = $document->getFileSize();
        $fileName = $document->getFileName();
        if ($fileSize > $maxSize) {
            throw new TelegramFileTooLargeException();
        }
        $file = $this->telegram->getFile(['file_id' => $fileId]);
        $fileUrl = $this->buildFileUrl($file->getFilePath());

        $storagePath = $this->downloadAndStoreFile($fileUrl, $fileName);

        return new TelegramFileDto(
            fileUrl: $storagePath,
            fileName: $fileName,
            fileSize: $fileSize,
        );
    }
}
