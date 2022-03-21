<?php
namespace App\Model;

use App\Helper\OutputHelper;
use PDO;

final class Level extends Model
{
    protected static $table = "levels";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "quest" => PDO::PARAM_STR,
        "answer" => PDO::PARAM_STR,
        "orders" => PDO::PARAM_INT,
        "difficulty" => PDO::PARAM_INT,
        "created_at" => PDO::PARAM_STR,
        "updated_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }

    public function check_level(string $text): bool
    {
        if ($text == $this->answer) {
            return true;
        }
        return false;
    }

    public static function get_last_order()
    {
        $last = self::get_first("", [], "ORDER BY orders DESC");
        if (!$last || empty($last)) {
            return 0;
        }
        return $last->orders;
    }

    public function auto_generate_hints()
    {
    }

    public function hint(int $_order)
    {
        return LevelHint::get_first(
            "WHERE level_id=:level_id AND orders>:orders",
            [":level_id" => $this->id, ":orders" => $_order],
            "ORDER BY orders asc"
        );
    }

    public function prize(): int
    {
        return 20;
    }

    public function on_create()
    {
        // notify to users for new mission
        $users = User::get_all("WHERE level=:level", [":level" => $this->orders]);
        foreach ($users as $user) {
            OutputHelper::new_level($user);
        }
    }
}
