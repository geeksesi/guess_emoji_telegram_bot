<?php
namespace App\Model;

use PDO;

final class Message extends Model
{
    protected static $table = "levels";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "chat_id" => PDO::PARAM_STR,
        "message" => PDO::PARAM_STR,
        "type" => PDO::PARAM_INT,
        "created_at" => PDO::PARAM_STR,
    ];
    protected static $statuses = ["add_level" => 3];

    public function __construct()
    {
    }

    public static function user_messages(int $_chat_id): array
    {
        return Message::get_all("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
    }

    public static function delete_after_complete(int $_chat_id): bool
    {
        return Message::delete_query("WHERE chat_id=:chat_id", ["chat_id" => $_chat_id]);
    }
}
