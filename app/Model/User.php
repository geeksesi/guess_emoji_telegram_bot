<?php
namespace App\Model;

final class User extends Model
{
    protected static $table = "users";
    protected static $fields = ["id", "chat_id", "credit", "level_id", "created_at", "updated_at"];

    public function __construct()
    {
    }

    public function level()
    {
        Level::get_first("WHERE id=:id", ["id" => $this->level_id]);
    }

    public static function get_or_create(string $_chat_id): self
    {
        $user = self::get_first("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
        if (!$user || empty($user)) {
            $level_id = Level::get_first("", [], "ORDER BY orders asc")->id ?? 0;
            self::create([
                "chat_id" => $_chat_id,
                "credit" => $_ENV["DEFAULT_CREDIT"],
                "level_id" => $level_id,
            ]);
            $user = self::get_first("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
        }
        return $user;
    }
}
