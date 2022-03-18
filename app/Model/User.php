<?php
namespace App\Model;

use PDO;

final class User extends Model
{
    protected static $table = "users";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "chat_id" => PDO::PARAM_STR,
        "credit" => PDO::PARAM_INT,
        "level" => PDO::PARAM_INT,
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
        return Level::get_first("WHERE orders=:orders", ["orders" => $this->level]);
    }

    public static function get_or_create(string $_chat_id): self
    {
        $user = self::get_first("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
        if (!$user || empty($user)) {
            $level_id = Level::get_first("", [], "ORDER BY orders asc")->order ?? 0;
            self::create([
                "chat_id" => $_chat_id,
                "credit" => $_ENV["DEFAULT_CREDIT"],
                "level" => $level_id,
            ]);
            $user = self::get_first("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
        }
        return $user;
    }
}
