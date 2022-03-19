<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Helper\LevelHintsHelper;
use App\Helper\TelegramHelper;
use App\Model\Level;

class AddLevelController extends Controller
{
    public function __invoke(): bool
    {
        $lines = explode("\n", $this->update["message"]["text"]);

        foreach ($lines as $line) {
            $this->handle_line($line);
        }
        return true;
        // json_encode($this->update, JSON_PRETTY_PRINT)
    }

    public function handle_line(string $line)
    {
        $sections = explode("-", $line);
        if (!isset($sections[1])) {
            return;
        }

        $question = trim($sections[0]) ?? throw new \Exception("Unvalid quest");
        $answer = trim($sections[1]) ?? throw new \Exception("Unvalid answer");
        $order = (int) $sections[2] ?? Level::get_last_order();

        $level = Level::create(["quest" => $question, "answer" => $answer, "orders" => $order]);
        // $auto_hints = LevelHintsHelper::generate($level);
        TelegramHelper::send_message("مرحله جدید : " . $level->quest . " :: " . $level->answer, $this->chat_id);
    }
}
