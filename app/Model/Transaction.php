<?php
namespace App\Model;

use PDO;

final class Transaction extends Model
{
    protected static $table = "users";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "balance" => PDO::PARAM_INT,
        "type" => PDO::PARAM_INT,
        "payment_id" => PDO::PARAM_INT,
        "created_at" => PDO::PARAM_STR,
    ];

    public function __construct()
    {
    }
    public function payment()
    {
        // Level::get_first('WHERE id=:id', ["id" => $this->level_id]);
    }
}
