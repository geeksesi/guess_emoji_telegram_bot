<?php

namespace App\Helper;

use App\Enums\OutputMessageEnum;
use App\Model\OutputMessage;

class OutputHelper
{
    public static function win_level(string $_chat_id)
    {
        $message = OutputMessage::random(OutputMessageEnum::LEVEL_WIN);
        return TelegramHelper::send_message($message->text, $_chat_id);
    }
}
