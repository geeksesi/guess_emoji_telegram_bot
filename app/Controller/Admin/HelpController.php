<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Helper\TelegramHelper;

class HelpController extends Controller
{
    public function __invoke(): bool
    {
        $message = "";
        $message .= "!help - help \n";
        $message .= "!aNewLevel - add new level \n";
        $message .= "!aNewHints - add new hint \n";
        $message .= "!listLevel - list of levels \n";
        $message .= "!listHints - list of hints \n";

        TelegramHelper::send_message($message, $this->chat_id);
        return true;
    }
}
