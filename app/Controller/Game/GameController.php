<?php

namespace App\Controller\Game;

use App\Controller\Controller;
use App\Helper\TelegramHelper;
use App\Model\User;

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
            TelegramHelper::send_message("تبریک شما برنده شدید 🥇", $this->chat_id);
            $this->user->next_level();
            return;
        }
        TelegramHelper::send_message("لطفا دوباره تلاش کنید 🙁", $this->chat_id);
    }

    public function question()
    {
        $level = $this->model->get_level($this->user["level_id"]);
        TelegramHelper::send_message($level["quest"], $this->update["message"]["chat"]["id"]);
    }
}
