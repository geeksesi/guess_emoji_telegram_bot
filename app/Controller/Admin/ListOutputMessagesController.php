<?php

namespace App\Controller\Admin;

use App\Enums\OutputMessageEnum;
use App\Helper\TelegramHelper;
use App\Model\LevelHint;
use App\Model\OutputMessage;

class ListOutputMessagesController extends Controller
{
    public function __invoke(): bool
    {
        $type = substr($this->update["message"]["text"], 11, 15) ?? 1;
        $type = empty($type) ? null : (int) $type;

        if ($type) {
            $messages = OutputMessage::get_all("WHERE type=:type", [":type" => $type]);
        } else {
            $this->list_of_types();
            return true;
        }

        $text_out = "";
        foreach ($messages as $message) {
            $text_out .= $this->single_output($message);
        }
        if (empty($text_out)) {
            TelegramHelper::send_message("NO records found", $this->chat_id);
            return true;
        }
        TelegramHelper::send_message($text_out, $this->chat_id);
        return true;
    }

    public function single_output(OutputMessage $message)
    {
        $output = "";
        $output .= $message->text . " \n\n ";

        return $output;
    }

    public function list_of_types()
    {
        $message = "";
        foreach (OutputMessageEnum::cases() as $key => $type) {
            $message .= $type->value . " :: " . $type->name;
            $message .= "\n";
        }
        TelegramHelper::send_message($message, $this->chat_id);
    }
}
