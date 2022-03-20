<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Helper\TelegramHelper;
use App\Model\OutputMessage;
use Exception;

class AddOutputMessageController extends Controller
{
    public function __invoke(): bool
    {
        $lines = explode("\n", $this->update["message"]["text"], 2);

        $type = substr($lines[0], 11, 15) ?? throw new Exception("Couldn't catch type");
        $type = OutputMessageEnum::from((int) $type);

        $this->handle_line($lines[1], $type);
        return true;
    }

    public function handle_line(string $line, OutputMessageEnum $type)
    {
        $text = trim($line) ?? throw new \Exception("Unvalid text");
        $result = OutputMessage::create([
            "text" => $text,
            "type" => $type->value,
        ]);
        // $auto_hints = LevelHintsHelper::generate($level);
        TelegramHelper::send_message(
            "متن خروجی جدید برای  : " . $type->name,
            $this->chat_id . " :: id = " . $result->id
        );
    }
}
