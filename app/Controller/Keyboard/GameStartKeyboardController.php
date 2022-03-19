<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;

class GameStartKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        // Start Message
        OutputHelper::by_type($this->chat_id, OutputMessageEnum::START);

        // Show Level
        $level = $this->user->level();
        OutputHelper::level($level, $this->chat_id);

        return true;
    }
}
