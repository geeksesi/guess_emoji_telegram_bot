<?php

namespace App\Controller\Command;

use App\Controller\Admin\Controller;
use App\Helper\TelegramHelper;

class ChatIdCommandController extends Controller
{
    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
    }

    public function __invoke(): bool
    {
        TelegramHelper::send_message((string) $this->chat_id, $this->chat_id);
        return true;
    }
}
