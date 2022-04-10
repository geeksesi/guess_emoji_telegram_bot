<?php

namespace App\Controller\Admin;

use App\Helper\TelegramHelper;

class HelpController extends Controller
{
    public function __invoke(): bool
    {
        $message = "";
        $message .= "!help - help \n";
        $message .= "!aNewLevel - add new level \n";
        $message .= "!listLevel - list of levels \n";
        $message .= "!aNewHints - add new hint \n";
        $message .= "!listHints - list of hints \n";
        $message .= "!aOuttexts - add new output message \n";
        $message .= "!listOMesg - list of output messages by types \n";
        $message .= "!newAds - reply to message to add advertise \n";
        $message .= "!getUserId - user id in each line give you user info \n";

        TelegramHelper::send_message($message, $this->chat_id);
        return true;
    }
}
