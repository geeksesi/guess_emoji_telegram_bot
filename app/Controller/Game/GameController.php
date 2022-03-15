<?php

namespace App\Controller\Game;

use App\Controller\Controller;
use App\Helper\TelegramHelper;
use App\Model\User;

class GameController extends Controller
{
    public function __invoke()
    {
    }

    public function run_game($_text)
    {
        $level = User::get_first($this->chat_id)->level();

        if ($level->check_level($_text)) {
            TelegramHelper::send_message("تبریک شما برنده شدید 🥇", $this->chat_id);
            $this->model->next_level($this->user["id"], $level["id"] + 1);
            $this->user = $this->model->get_user($this->chat_id);

            return;
        }
        TelegramHelper::send_message("لطفا دوباره تلاش کنید 🙁", $this->chat_id);
    }

    public function start_cmd()
    {
        $keyboard = TelegramHelper::make_keyboard([[["text" => "دیدن سوال "]]]);
        $text = "
        سلام خوش آمدید به ربات بازی حدس ایموجی.\n

        برای دیدن سوال روی دکمه زیر کلیک کنید.
        ";
        TelegramHelper::send_message($text, $this->update["message"]["chat"]["id"], $keyboard);
    }

    public function question()
    {
        $level = $this->model->get_level($this->user["level_id"]);
        TelegramHelper::send_message($level["quest"], $this->update["message"]["chat"]["id"]);
    }
}
