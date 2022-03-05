<?php
namespace App\Model;

final class Transaction extends Model
{
    protected static $table = 'users';
    protected static $fields = ['id', 'balance', 'type', 'payment_id', 'created_at'];

    public function __construct()
    {
    }
    public function payment()
    {
        // Level::get_first('WHERE id=:id', ["id" => $this->level_id]);
    }
}
