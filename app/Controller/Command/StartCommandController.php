<?php

namespace App\Controller\Command;

use App\Controller\Controller;
use App\Helper\TelegramHelper;

class StartCommandController extends Controller
{
    public function __invoke(): bool
    {
        $keyboard = TelegramHelper::make_keyboard([[["text" => "شروع بازی"]]]);
        $text = "
        سلام خوش آمدید به ربات بازی حدس ایموجی.\n

        برای دیدن سوال روی دکمه زیر کلیک کنید.
        ";
        TelegramHelper::send_message($text, $this->update["message"]["chat"]["id"], $keyboard);
        return true;
    }
}
