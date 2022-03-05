<?php
namespace App\Model;

final class Message extends Model
{
    protected static $table = 'levels';
    protected static $fields = ['id', 'chat_id', 'message', 'type', 'created_at'];
    protected static $statuses = ['add_level' => 3];

    public function __construct()
    {
    }

    public static function user_messages(int $_chat_id): array
    {
        return Message::get_all('WHERE chat_id=:chat_id', ["chat_id" => $_chat_id]);
    }

    public static function delete_after_complete(int $_chat_id): bool
    {
        return Message::delete_query('WHERE chat_id=:chat_id', ["chat_id" => $_chat_id]);
    }
}
