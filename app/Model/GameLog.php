<?php
namespace App\Model;

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
}
