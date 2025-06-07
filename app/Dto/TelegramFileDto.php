<?php

namespace App\Dto;

class TelegramFileDto
{
    public function __construct(
        public string $fileUrl,
        public string $fileName,
        public int $fileSize
    ) {}
}
