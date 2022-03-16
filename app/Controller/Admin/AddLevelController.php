<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Helper\LevelHintsHelper;
use App\Helper\TelegramHelper;
use App\Model\Level;

class AddLevelController extends Controller
{
    public function error(string $_text)
    {
        TelegramHelper::send_message($_text, $this->chat_id);
        return false;
    }
    public function __invoke()
    {
        $lines = explode("\n", $this->update["message"]["text"]);

        foreach ($lines as $line) {
            $this->handle_line($line);
        }
        // json_encode($this->update, JSON_PRETTY_PRINT)
    }

    public function handle_line(string $line)
    {
        $sections = explode("-", $line);
        if (!isset($sections[1])) {
            return;
        }

        $question = $sections[0] ?? $this->error("Unvalid quest");
        $answer = $sections[1] ?? $this->error("Unvalid answer");
        $order = (int) $sections[2] ?? Level::get_last_order();

        $level = Level::create(["quest" => $question, "answer" => $answer, "orders" => $order]);
        $auto_hints = LevelHintsHelper::generate($level);

        TelegramHelper::send_message($line, json_encode($level, JSON_UNESCAPED_UNICODE & JSON_PRETTY_PRINT));
        TelegramHelper::send_message($line, json_encode($auto_hints, JSON_UNESCAPED_UNICODE & JSON_PRETTY_PRINT));
    }
}
