<?php
namespace App\Model;

use PDO;

final class Payment extends Model
{
    protected static $table = "payments";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "user_id" => PDO::PARAM_INT,
        "plan_id" => PDO::PARAM_INT,
        "payment_key" => PDO::PARAM_STR,
        "credit" => PDO::PARAM_INT,
        "cost" => PDO::PARAM_INT,
        "status" => PDO::PARAM_INT,
        "created_at" => PDO::PARAM_STR,
        "updated_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }

    public static function get(int $id)
    {
        return self::get_first("WHERE id=:id AND status=1", [":id" => $id]);
    }
}
