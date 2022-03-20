<?php

namespace App\Controller\Command;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\KeyboardMakerHepler;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;

class StartCommandController extends Controller
{
    public function __invoke(): bool
    {
        OutputHelper::by_type($this->chat_id, OutputMessageEnum::START_COMMAND);
        return true;
    }
}
