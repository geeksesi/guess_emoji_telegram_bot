<?php

namespace App\Controller\Admin;

use App\Helper\TelegramHelper;

class GetUserByChatIdController extends Controller
{
    public function __invoke(): bool
    {
        $lines = explode("\n", $this->update["message"]["text"]) ?? [];
        foreach ($lines as $key => $line) {
            if ($key === 0) {
                continue;
            }
            $user = TelegramHelper::get_user($line);
            TelegramHelper::send_message(json_encode($user), $this->chat_id);
        }

        return true;
    }
}
