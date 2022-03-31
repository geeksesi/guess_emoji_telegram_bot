<?php
namespace App\Model;

use PDO;

final class Plan extends Model
{
    protected static $table = "plans";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "name" => PDO::PARAM_STR,
        "description" => PDO::PARAM_STR,
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
