<?php

namespace App\Controller\Admin;

use App\Enums\LevelHintsTypeEnum;
use App\Helper\LevelHintsHelper;
use App\Helper\TelegramHelper;
use App\Model\Level;
use App\Model\LevelHint;
use Exception;

class AddHintController extends Controller
{
    public function __invoke(): bool
    {
        $lines = explode("\n", $this->update["message"]["text"]);
        $level_id = substr($lines[0], 11, 15) ?? throw new Exception("Couldn't catch level_id");
        $level_id = (int) $level_id;

        $level = Level::find($level_id);

        foreach ($lines as $line) {
            $this->handle_line($line, $level);
        }
        return true;
    }

    public function handle_line(string $line, Level $level)
    {
        $sections = explode("-", $line);
        if (!isset($sections[1])) {
            return;
        }
        $hint = trim($sections[0]) ?? throw new \Exception("Unvalid hint");
        $order = (int) trim($sections[1]) ?? throw new \Exception("Unvalid order");

        $level_hint = LevelHint::create([
            "hint" => $hint,
            "level_id" => $level->id,
            "orders" => $order,
            "type" => LevelHintsTypeEnum::DESCRIPTION->value,
        ]);
        // $auto_hints = LevelHintsHelper::generate($level);
        TelegramHelper::send_message("راهنمایی جدید : " . $level->quest . " :: " . $level_hint->hint, $this->chat_id);
    }
}
