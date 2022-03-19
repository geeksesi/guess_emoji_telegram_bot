<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Helper\TelegramHelper;
use App\Model\LevelHint;

class ListHintsController extends Controller
{
    public function __invoke(): bool
    {
        $level_id = substr($this->update["message"]["text"], 11, 15) ?? 1;
        $level_id = empty($level_id) ? null : (int) $level_id;

        if ($level_id) {
            $hints = LevelHint::get_all("WHERE level_id=:level_id", [":level_id" => $level_id], "ORDER BY orders ASC");
        } else {
            $hints = LevelHint::get_all("", [], "ORDER BY level_id ASC");
        }

        $text_out = "";
        foreach ($hints as $hint) {
            $text_out .= $this->single_output($hint);
        }

        TelegramHelper::send_message($text_out, $this->chat_id);
        return true;
    }

    public function single_output(LevelHint $hint)
    {
        $output = "مرحله : ";
        $output .= $hint->level()->quest . " - ";
        $output .= $hint->orders . " :: ";
        $output .= $hint->hint . "  \n ";

        return $output;
    }
}
