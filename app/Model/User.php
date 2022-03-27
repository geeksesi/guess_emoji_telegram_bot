<?php
namespace App\Model;

use App\Enums\OutputMessageEnum;
use App\Helper\OutputHelper;
use PDO;

final class User extends Model
{
    protected static $table = "users";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "chat_id" => PDO::PARAM_STR,
        "credit" => PDO::PARAM_INT,
        "level_id" => PDO::PARAM_INT,
        "invite_key" => PDO::PARAM_STR,
        "created_at" => PDO::PARAM_STR,
        "updated_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }

    public function level()
    {
        $this->next_level();
        $level = Level::get_first("WHERE id=:id", ["id" => $this->level_id]);
        return $level;
    }

    public static function get_or_create(string $_chat_id): self
    {
        $user = self::get_first("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
        if (!$user || empty($user)) {
            $user = self::create([
                "chat_id" => $_chat_id,
                "credit" => $_ENV["DEFAULT_CREDIT"],
            ]);
        }
        return $user;
    }

    public function next_level(): Level|bool
    {
        $next_level = Level::get_first(
            "WHERE id NOT IN (SELECT level_id FROM game_logs WHERE user_id=:user_id) AND difficulty <= (SELECT MAX(difficulty) FROM levels WHERE id IN (SELECT level_id FROM game_logs WHERE user_id=:user_id) )",
            [":user_id" => $this->id, ":user_id" => $this->id],
            "ORDER BY RAND()"
        );
        $this->level_id = $next_level->id ?? null;
        $this->save();
        if ($this->level_id) {
            $level = $this->level();
            return $level;
        }
        return null;
    }

    public function hint_cost(): int
    {
        $cost = 25;
        $hint_count = $this->hint_count();
        $cost = $cost + $hint_count * 14;
        return $cost;
    }

    public function hint_count()
    {
        return GameLog::get_first(
            "WHERE user_id=:user_id AND level_id=:level_id",
            [":user_id" => $this->id, ":level_id" => $this->level()->id],
            "ORDER BY hint_count DESC"
        )->hint_count ?? 0;
    }

    public function invite_link(): string
    {
        if (empty($this->invite_key)) {
            $this->invite_key = uniqid();
            $this->save();
        }
        return $_ENV["BOT_LINK"] . "?start=" . $this->invite_key;
    }
}
