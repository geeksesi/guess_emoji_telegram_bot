<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;

class BuyCreditKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        // Show plans

        $keyboard["inline_keyboard"] = [
            [
                [
                    "text" => "مشاهده آگهی",
                    "url" => $_url,
                ],
            ],
        ];

        TelegramHelper::send_message("در دست احداث", $this->chat_id);
        return true;
    }
}
