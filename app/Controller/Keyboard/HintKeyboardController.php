<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;

use App\Helper\TelegramHelper;

class HintKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        TelegramHelper::send_message("در دست احداث", $this->chat_id);

        return true;
    }
}
