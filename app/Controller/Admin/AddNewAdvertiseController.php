<?php

namespace App\Controller\Admin;

use App\Helper\TelegramHelper;
use App\Model\Advertise;

class AddNewAdvertiseController extends Controller
{
    public function __invoke(): bool
    {
        if (!isset($this->update["message"]["reply_to_message"])) {
            TelegramHelper::send_message("WRONG USAGE, please do reply", $this->chat_id);
            return true;
        }

        $reply = $this->update["message"]["reply_to_message"];
        Advertise::create([
            "gift_credit" => 15,
            "status" => 1,
            "message_id" => $reply["message_id"],
            "from_chat_id" => $reply["chat"]["id"],
        ]);

        return true;
    }
}
