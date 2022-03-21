<?php

namespace App\Controller\Game;

use App\Controller\Controller;
use App\Enums\TransactionTypeEnum;
use App\Helper\OutputHelper;
use App\Helper\TelegramHelper;
use App\Model\GameLog;
use App\Model\Transaction;

class GameController extends Controller
{
    public function __invoke(): bool
    {
        $this->run_game($this->update["message"]["text"]);
        return true;
    }

    public function run_game($_text)
    {
        $level = $this->user->level();
        if (!$level) {
            return true;
        }
        GameLog::create([
            "user_id" => $this->user->id,
            "level_id" => $level->id,
            "hint_count" => $this->user->hint_count(),
        ]);
        if ($level->check_level($_text)) {
            $prize = $level->prize();
            OutputHelper::win_level($this->user, $prize);

            // to the next level
            $this->user->next_level();

            // Prize
            // add Transaction
            $transaction = Transaction::create([
                "balance" => $prize,
                "type" => TransactionTypeEnum::WIN_LEVEL->value,
                "user_id" => $this->user->id,
            ]);
            // calculate credit
            $this->user->credit = $transaction->credit_calculate();
            $this->user->save();

            return;
        }
        OutputHelper::lose_level($this->user);
    }
}
