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
        $level = User::get_first("WHERE chat_id=:chat_id", ["chat_id" => $this->chat_id])->level();
        // var_dump($level);
        // die();
        if ($level->check_level($_text)) {
            TelegramHelper::send_message("تبریک شما برنده شدید 🥇", $this->chat_id);
            $this->model->next_level($this->user["id"], $level["id"] + 1);
            $this->user = $this->model->get_user($this->chat_id);

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
