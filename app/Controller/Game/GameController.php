<?php

namespace App\Controller\Game;

use App\Controller\Controller;
use App\Enums\GameLogActionEnum;
use App\Enums\OutputMessageEnum;
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
            OutputHelper::level($this->user);

            return true;
        }

        $score = $level->check_level($_text);
        if ($score == 100) {
            // Prize
            $prize = $level->prize();
            GameLog::action($level, $this->user, GameLogActionEnum::WIN, $prize);
            // to the next level
            $level = $this->user->next_level();
            OutputHelper::win_level($this->user, $prize);
            // add Transaction
            $transaction = Transaction::create([
                "balance" => $prize,
                "type" => TransactionTypeEnum::WIN_LEVEL->value,
                "user_id" => $this->user->id,
            ]);
            // calculate credit
            $this->user->credit = $transaction->credit_calculate();
            $this->user->save();

            if (empty($level)) {
                OutputHelper::by_type($this->chat_id, OutputMessageEnum::FINISH_GAME);
                TelegramHelper::send_message("SOME BODY FINISHED GAME 😦", $_ENV["ADMIN"]);
            }

            return;
        } elseif ($score > 75) {
            OutputHelper::close_level($this->user);
        } else {
            OutputHelper::lose_level($this->user);
        }

        GameLog::action($level, $this->user, GameLogActionEnum::LOSE);
    }
}
