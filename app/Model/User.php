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
        "level" => PDO::PARAM_INT,
        "invite_key" => PDO::PARAM_STR,
        "created_at" => PDO::PARAM_STR,
        "updated_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }

    public function level()
    {
        if ($this->level === 0) {
            $this->level = 1;
            $this->save();
        }
        $level = Level::get_first("WHERE orders=:orders", ["orders" => $this->level]);
        return $level;
    }

    public static function get_or_create(string $_chat_id): self
    {
        $user = self::get_first("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
        if (!$user || empty($user)) {
            $level_id = Level::get_first("", [], "ORDER BY orders asc")->order ?? 0;
            $user = self::create([
                "chat_id" => $_chat_id,
                "credit" => $_ENV["DEFAULT_CREDIT"],
                "level" => $level_id,
                "invite_key" => uniqid(),
            ]);
        }
        return $user;
    }

    public function next_level(): Level|bool
    {
        $this->level = $this->level + 1;
        $this->save();
        $level = $this->level();
        return $level;
    }

    public function hint_cost(): int
    {
        return 25;
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
