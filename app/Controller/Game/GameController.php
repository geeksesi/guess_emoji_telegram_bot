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
    public function __invoke()
    {
        $this->run_game($this->update["message"]["text"]);
    }

    public function run_game($_text)
    {
        $level = $this->user->level();
        GameLog::create([
            "user_id" => $this->user->id,
            "level_id" => $level->id,
            "hint_count" => $this->user->hint_count(),
        ]);
        if ($level->check_level($_text)) {
            // to the next level
            $this->user->next_level();

            // Prize
            $prize = $level->prize();
            // add Transaction
            $transaction = Transaction::create([
                "balance" => $prize,
                "type" => TransactionTypeEnum::WIN_LEVEL,
                "user_id" => $this->user->id,
            ]);
            // calculate credit
            $this->user->credit = $transaction->credit_calculate();
            $this->user->save();

            // output :)
            OutputHelper::win_level($this->user, $prize);
            return;
        }
        OutputHelper::lose_level($this->user);
    }
}
