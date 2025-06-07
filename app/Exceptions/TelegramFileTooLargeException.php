<?php

namespace App\Exceptions;

use Exception;

class TelegramFileTooLargeException extends Exception
{
    protected $message = 'File too large. Max allowed size is 5MB.';
}
