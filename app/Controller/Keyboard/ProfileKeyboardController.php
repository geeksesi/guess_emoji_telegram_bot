<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;

class ProfileKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        OutputHelper::self_profile($this->chat_id, $this->user, true);

        return true;
    }
}
