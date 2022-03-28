<?php

namespace App\Controller\Keyboard;

use App\Controller\Controller;
use App\Enums\GameLogActionEnum;
use App\Enums\OutputMessageEnum;
use App\Enums\TransactionTypeEnum;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;
use App\Model\GameLog;
use App\Model\Transaction;

class HintKeyboardController extends Controller
{
    public function __invoke(): bool
    {
        // Get Cost And Compare
        $cost = $this->user->hint_cost();
        $credit = $this->user->credit;
        // if has not enough
        if ($cost > $credit) {
            OutputHelper::by_type($this->chat_id, OutputMessageEnum::LOW_CREDIT);
            return true;
        }
        // fetch Hint
        $level = $this->user->level();
        $hint_count = $this->user->hint_count();
        $hint = $level->hint($hint_count);

        // if there is no hint say this
        if (!$hint) {
            OutputHelper::by_type($this->chat_id, OutputMessageEnum::NO_HINT);
            return true;
        }

        // add Transaction
        $transaction = Transaction::create([
            "balance" => $cost * -1,
            "type" => TransactionTypeEnum::HINT_COST->value,
            "user_id" => $this->user->id,
        ]);
        // calculate credit
        $this->user->credit = $transaction->credit_calculate();
        $this->user->save();

        // update user level try
        GameLog::action($level, $this->user, GameLogActionEnum::HINT, $cost * -1);

        // send hint
        TelegramHelper::send_message($hint->hint, $this->chat_id);
        OutputHelper::level($this->user);

        return true;
    }
}
