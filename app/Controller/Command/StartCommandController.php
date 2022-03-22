<?php

namespace App\Controller\Command;

use App\Controller\Controller;
use App\Enums\OutputMessageEnum;
use App\Enums\TransactionTypeEnum;
use App\Helper\KeyboardMakerHepler;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;
use App\Model\Transaction;
use App\Model\User;
use PDO;

class StartCommandController extends Controller
{
    public function __construct(array $update)
    {
        $this->update = $update;
        $this->chat_id = $this->update["message"]["chat"]["id"];
    }

    public function __invoke(): bool
    {
        // check user existing
        $user = User::get_first("WHERE chat_id=:chat_id", [":chat_id" => $this->chat_id]);

        if ($user) {
            // if exist OutputMessageEnum::START_COMMAND_USER
            OutputHelper::by_type($this->chat_id, OutputMessageEnum::START_COMMAND_USER);
            return true;
        }
        $this->user = User::get_or_create($this->chat_id);
        // check is start has second option (for invite link)
        OutputHelper::by_type($this->chat_id, OutputMessageEnum::START_COMMAND_GUEST);
        $ex = explode(" ", $this->update["message"]["text"]);
        if (isset($ex[1])) {
            $this->handle_invation($ex[1]);
        }

        return true;
    }

    public function handle_invation(string $_key)
    {
        $parent = User::get_first("WHERE invite_key=:invite_key", [":invite_key" => $_key]);
        if (!$parent) {
            return;
        }
        // user gift
        $transaction = Transaction::create([
            "balance" => 40,
            "type" => TransactionTypeEnum::FRIEND_GIFT_BACK->value,
            "user_id" => $this->user->id,
        ]);
        // calculate credit
        $this->user->credit = $transaction->credit_calculate();
        $this->user->save();
        OutputHelper::by_type($this->chat_id, OutputMessageEnum::FRIEND_INVITE_GIFT_BACK);

        // parent gift
        $transaction = Transaction::create([
            "balance" => 80,
            "type" => TransactionTypeEnum::FRIEND_GIFT_BACK->value,
            "user_id" => $parent->id,
        ]);
        // calculate credit
        $parent->credit = $transaction->credit_calculate();
        $parent->save();
        OutputHelper::by_type($parent->chat_id, OutputMessageEnum::INVATION_SUCCESS);
    }
}
