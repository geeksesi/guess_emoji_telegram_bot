<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Helper\TelegramHelper;

class AddLevelController extends Controller
{
    public function __invoke()
    {
        TelegramHelper::send_message("HELLOa", $this->update["message"]["chat"]["id"]);
    }
}
