<?php

class Controller
{
    private $update;
    private $chat_id;
    private $model;

    public function __construct()
    {
        $this->model = new Model();
    }

    public function check_predefine_messages($text)
    {
        switch ($text) {
            case '/start':
                $this->start_cmd();
                break;
            case 'دیدن سوال':
                $this->starter_question();
                break;

            default:
                $this->run_game($text);
                break;
        }
    }

    public function run_game($_text)
    {
        if ($_text === 'بالون') {
            TelegraLib::send_message('تبریک شما برنده شدید 🥇', $this->chat_id);
            return;
        }
        TelegraLib::send_message('لطفا دوباره تلاش کنید 🙁', $this->chat_id);
    }

    public function handle($update)
    {
        $this->update = $update;
        $this->chat_id = $this->update['message']['chat']['id'];
        $text = $update['message']['text'];

        var_dump($text);
        $this->check_predefine_messages($text);
    }

    public function start_cmd()
    {
        $keyboard = TelegraLib::make_keyboard([[['text' => 'دیدن سوال ']]]);
        $text = "
        سلام خوش آمدید به ربات بازی حدس ایموجی.\n

        برای دیدن سوال روی دکمه زیر کلیک کنید.
        ";
        TelegraLib::send_message(
            $text,
            $this->update['message']['chat']['id'],
            $keyboard
        );
    }

    public function starter_question()
    {
        TelegraLib::send_message(
            '🏀🔛',
            $this->update['message']['chat']['id']
        );
    }

    public function create_level_request()
    {
        $text = filter_var(
            $this->update['message']['text'],
            FILTER_SANITIZE_STRING
        );
        list($question, $answer) = explode(' , ', $text);
        $this->model->add_level($question, $answer);
    }

    public function create_level()
    {
        $text = filter_var(
            $this->update['message']['text'],
            FILTER_SANITIZE_STRING
        );
        list($question, $answer) = explode(' , ', $text);
        $this->model->add_level($question, $answer);
    }
}
