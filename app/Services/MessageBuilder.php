<?php

namespace App\Services;

class MessageBuilder
{
    // Example message keys
    const GREETING = 'greeting';
    const FILE_TOO_LARGE = 'file_too_large';
    const FILE_ATTACHMENT_PROMPT = 'file_attachment_prompt';
    const TICKET_CREATED = 'ticket_created';
    const ERROR = 'error';

    protected array $messages = [
        self::GREETING => 'Welcome to the support bot! Please enter the subject of your ticket.',
        self::FILE_TOO_LARGE => 'The file you attached is too large. Maximum allowed size is 5MB.',
        self::FILE_ATTACHMENT_PROMPT => 'Please provide the message body for your ticket (max 2000 chars). You can also attach a file (max 5MB).',
        self::TICKET_CREATED => 'Your ticket has been created successfully! Ticket number: :number',
        self::ERROR => 'An error occurred. Please try again later.',
    ];

    public function get(string $key, array $replace = []): string
    {
        $message = $this->messages[$key] ?? $key;
        foreach ($replace as $search => $value) {
            $message = str_replace(':' . $search, $value, $message);
        }
        return $message;
    }
}
