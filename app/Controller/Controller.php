<?php

namespace App\Controller;

use App\Controller\Command\StartCommandController;
use App\Enums\OutputMessageEnum;
use App\Enums\TransactionTypeEnum;
use App\Helper\OutputHelper;
use App\Helper\RedisHelper;
use App\Helper\TelegramHelper as HelperTelegramHelper;
use App\Model\Advertise;
use App\Model\Transaction;
use App\Model\User;
use App\TelegramHelper;

abstract class Controller
{
    protected array $update;
    protected string $chat_id;
    protected User $user;

    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
        $this->user = User::get_first("WHERE chat_id=:chat_id", [":chat_id" => $this->chat_id]);
        RedisHelper::user_message_count($this->user, true);
        $this->advertisement();
    }

    public function advertisement()
    {
        $message_count = RedisHelper::user_message_count($this->user, false);
        if ($message_count % 5 != 0) {
            var_dump("WRONG !");
            return;
        }
        $div = $message_count / 5;
        if ($div != 1 && $div % 4 != 0) {
            var_dump("WRONG @", $div);
            return;
        }
        // Do advertisement :)
        $advertise = Advertise::get_first(
            "WHERE id NOT IN (SELECT advertise_id FROM transactions WHERE advertise_id IS NOT NULL AND user_id=:user_id) AND  status=:status",
            [":user_id" => $this->user->id, ":status" => 1]
        );
        if (!$advertise) {
            var_dump("NO AD");
            return;
        }

        if (!HelperTelegramHelper::forward($this->chat_id, $advertise->from_chat_id, $advertise->message_id)) {
            var_dump("FORWARD PROBLEM");
            return;
        }
        var_dump("HELLO");

        $transaction = Transaction::create([
            "balance" => $advertise->gift_credit,
            "type" => TransactionTypeEnum::ADVERTISE_GIFT_CREDIT->value,
            "user_id" => $this->user->id,
            "advertise_id" => $advertise->id,
        ]);
        // calculate credit
        $this->user->credit = $transaction->credit_calculate();
        $this->user->save();

        OutputHelper::by_type($this->chat_id, OutputMessageEnum::ADVERTISE_GIFT_CREDIT, true, [
            "+-CREDIT-+" => $advertise->gift_credit,
        ]);
    }
}
