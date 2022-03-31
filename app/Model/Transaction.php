<?php
namespace App\Model;

use PDO;

final class Transaction extends Model
{
    protected static $table = "transactions";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "balance" => PDO::PARAM_INT,
        "type" => PDO::PARAM_INT,
        "user_id" => PDO::PARAM_INT,
        "advertise_id" => PDO::PARAM_INT,
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

    public function credit_calculate()
    {
        // this should be like a query and calculate all of user transactions :)  this is demo
        return $this->user()->credit + $this->balance;
    }

    public function user()
    {
        return User::get_first("WHERE id=:id", [":id" => $this->user_id]);
    }
}
