<?php
namespace App\Model;

use PDO;

final class Advertise extends Model
{
    protected static $table = "advertises";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "gift_credit" => PDO::PARAM_INT,
        "status" => PDO::PARAM_INT,
        "message_id" => PDO::PARAM_STR,
        "from_chat_id" => PDO::PARAM_STR,
        "created_at" => PDO::PARAM_STR,
        "updated_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }
}
