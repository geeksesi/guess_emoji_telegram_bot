<?php
namespace App\Model;

final class User extends Model
{
    protected static $table = 'users';
    protected static $fields = ['id', 'chat_id', 'credit', 'level_id', 'created_at', 'updated_at'];

    public function __construct()
    {
    }
    public function level()
    {
        Level::get_first('WHERE id=:id', ["id" => $this->level_id]);
    }
}