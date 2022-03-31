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

    public function user()
    {
        return User::get_first("WHERE id=:id", [":id" => $this->user_id]);
    }
}
