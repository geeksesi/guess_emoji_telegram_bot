<?php
namespace App\Model;

final class Level extends Model
{
    protected static $table = "levels";
    protected static $fields = ["id", "quest", "answer", "orders", "difficulty", "created_at", "updated_at"];

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
}
