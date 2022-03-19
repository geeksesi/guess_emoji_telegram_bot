<?php
namespace App\Model;

use PDO;

final class LevelHint extends Model
{
    protected static $table = "level_hints";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "hint" => PDO::PARAM_STR,
        "level_id" => PDO::PARAM_INT,
        "orders" => PDO::PARAM_INT,
        "type" => PDO::PARAM_STR,
        "created_at" => PDO::PARAM_STR,
        "updated_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }

    public function level(): Level
    {
        return Level::get_first("WHERE id=:id", ["id" => $this->level_id]);
    }
}
