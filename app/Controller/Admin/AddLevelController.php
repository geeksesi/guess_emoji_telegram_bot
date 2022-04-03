<?php

namespace App\Controller\Admin;

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
        $difficualty = (int) trim($sections[2]) ?? 1;

        $level = Level::create(["quest" => $question, "answer" => $answer, "difficulty" => $difficualty]);
        // $auto_hints = LevelHintsHelper::generate($level);
        TelegramHelper::send_message("مرحله جدید : " . $level->quest . " :: " . $level->answer . "\n لطفا طبق ساختار زیر راهنمایی اضافه کنید", $this->chat_id);
        TelegramHelper::send_message("!aNewHints " . $level->id . " \n HINT - ORDER \n HINT - ORDER \n HINT - ORDER \n HINT - ORDER", $this->chat_id);

    }
}
