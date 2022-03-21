<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;

class GameContinueKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        // Start Message
        OutputHelper::by_type($this->chat_id, OutputMessageEnum::CONTINUE, true);

        // Show Level
        OutputHelper::level($this->user);

        return true;
    }
}
