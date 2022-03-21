<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;

class FreeCreditKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        OutputHelper::free_credit($this->user);

        return true;
    }
}
