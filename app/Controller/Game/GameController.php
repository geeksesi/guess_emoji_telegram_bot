<?php

namespace App\Controller\Game;

use App\Controller\Controller;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;

class GameController extends Controller
{
    public function __invoke()
    {
        $this->run_game($this->update["message"]["text"]);
    }

    public function run_game($_text)
    {
        $level = $this->user->level();
        if ($level->check_level($_text)) {
            $this->user->next_level();
            OutputHelper::win_level($this->user);
            return;
        }
        OutputHelper::lose_level($this->user);
    }
}
