<?php

namespace App\Controller\Admin;

use App\Controller\Controller;
use App\Helper\LevelHintsHelper;
use App\Helper\TelegramHelper;
use App\Model\Level;

class ListLevelsController extends Controller
{
    public function __invoke(): bool
    {
        $page = substr($this->update["message"]["text"], 11, 15) ?? 1;
        $page = empty($page) ? 1 : (int) $page;

        $levels = Level::get_paginate("", [], "ORDER BY orders ASC", $page);

        $text_out = "";
        foreach ($levels as $level) {
            $text_out .= $this->single_level_output($level);
        }

        TelegramHelper::send_message($text_out, $this->chat_id);
        return true;
    }

    public function single_level_output(Level $level)
    {
        $output = "مرحله : ";
        $output .= $level->orders . " - ";
        $output .= $level->quest . " ⬅️ ";
        $output .= $level->answer . " 😵 ";
        $output .= $level->difficulty . " \n ";

        return $output;
    }
}
