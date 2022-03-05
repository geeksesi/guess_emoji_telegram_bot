<?php

namespace App\Controller;

use App\Model\User;
use App\TelegramHelper;

class Controller
{
    private $update;
    private $chat_id;
    private $user;

    public function __construct()
    {
    }

    public function check_predefine_messages($text)
    {
        switch ($text) {
            case '/start':
                $this->start_cmd();
                break;
            case 'دیدن سوال':
                $this->question();
                break;

            default:
                $this->run_game($text);
                $this->question();
                break;
        }
    }

    public function run_game($_text)
    {
        $level = User::get_first($this->chat_id)->level();

        if ($level->check_level($_text)) {
            TelegramHelper::send_message('تبریک شما برنده شدید 🥇', $this->chat_id);
            $this->model->next_level($this->user['id'], $level['id'] + 1);
            $this->user = $this->model->get_user($this->chat_id);

            return;
        }
        TelegramHelper::send_message('لطفا دوباره تلاش کنید 🙁', $this->chat_id);
    }

    public function handle($update)
    {
        $this->update = $update;
        $this->chat_id = $this->update['message']['chat']['id'];
        $this->user = $this->model->get_user($this->chat_id);

        $text = $update['message']['text'];

        // var_dump($text);
        $this->check_predefine_messages($text);
    }

    public function start_cmd()
    {
        $keyboard = TelegramHelper::make_keyboard([[['text' => 'دیدن سوال ']]]);
        $text = "
        سلام خوش آمدید به ربات بازی حدس ایموجی.\n

        برای دیدن سوال روی دکمه زیر کلیک کنید.
        ";
        TelegramHelper::send_message($text, $this->update['message']['chat']['id'], $keyboard);
    }

    public function question()
    {
        $level = $this->model->get_level($this->user['level_id']);
        TelegramHelper::send_message($level['quest'], $this->update['message']['chat']['id']);
    }
}
