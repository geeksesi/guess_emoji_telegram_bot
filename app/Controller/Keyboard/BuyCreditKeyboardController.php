<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Helper\TelegramHelper;
use App\Model\Plan;

class BuyCreditKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        // Show plans
        $plans = Plan::get_all("WHERE status=:status", [":status" => 1], "ORDER BY credit ASC");
        foreach ($plans as $plan) {
            $message = "بسته : " . $plan->name;
            $message .= "\n";
            $message .= $plan->description;
            $keyboard = [];
            $keyboard["inline_keyboard"] = [
                [
                    [
                        "text" => "خرید " . $plan->credit . " سکه با " . $plan->cost . " تومان",

                        "url" => $_ENV["HTTP_URL"] . "/payment.php?key=" . $this->chat_id . "&plan=" . $plan->id,
                    ],
                ],
            ];

            TelegramHelper::send_message($message, $this->chat_id, $keyboard);
        }

        return true;
    }
}
