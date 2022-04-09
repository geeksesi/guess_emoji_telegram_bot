<?php
namespace App\Model;

use App\Enums\GameLogActionEnum;
use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;
use PDO;

final class GameLog extends Model
{
    protected static $table = "game_logs";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "user_id" => PDO::PARAM_INT,
        "level_id" => PDO::PARAM_INT,
        "hint_count" => PDO::PARAM_INT,
        "try" => PDO::PARAM_INT,
        "balance" => PDO::PARAM_INT,
        "last_action" => PDO::PARAM_INT,
        "created_at" => PDO::PARAM_INT,
    ];

    public function __construct()
    {
    }

    public function level()
    {
        return Level::get_first("WHERE orders=:orders", ["orders" => $this->level_id]);
    }

    public function user()
    {
        return User::get_first("WHERE id=:id", ["id" => $this->user_id]);
    }

    public static function action(Level $level, User $user, GameLogActionEnum $action, int $cost = 0)
    {
        $log = self::get_first("WHERE user_id=:user_id AND level_id=:level_id", [
            ":user_id" => $user->id,
            ":level_id" => $level->id,
        ]);
        if (!$log) {
            $log = self::create([
                "user_id" => $user->id,
                "level_id" => $level->id,
                "hint_count" => 0,
                "try" => 0,
                "balance" => 0,
                "last_action" => GameLogActionEnum::START->value,
            ]);
        }
        $log->last_action = $action->value;
        switch ($action) {
            case GameLogActionEnum::LOSE:
                $log->try += 1;
                break;
            case GameLogActionEnum::WIN:
                $log->try += 1;
                $log->balance += $cost;
                break;
            case GameLogActionEnum::HINT:
                $log->hint_count += 1;
                $log->balance += $cost;
                break;
        }
        $log->save();
    }

    public static function user_level_count(User $user): int
    {
        $table = self::$table;
        $db = self::connection();

        $query = $db->prepare("SELECT count(*) FROM {$table} WHERE user_id=:user_id");

        if (!$query->execute([":user_id" => $user->id])) {
            return 0;
        }
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result["count(*)"];
    }
}
