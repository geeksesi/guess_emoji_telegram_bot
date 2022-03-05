<?php

namespace App\Helper;

use App\Model\OutputMessage;
use OutputMessageEnum;

class OutputHelper
{
    public static function win_level(string $_chat_id)
    {
        $message = OutputMessage::random(OutputMessageEnum::LEVEL_WIN);
        return TelegramHelper::send_message($message->text, $_chat_id);
    }
}
