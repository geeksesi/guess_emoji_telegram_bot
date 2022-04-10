<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;

class LeaderBoardKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        OutputHelper::leader_board($this->chat_id);

        return true;
    }
}
