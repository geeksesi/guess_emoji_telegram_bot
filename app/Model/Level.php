<?php
namespace App\Model;

final class Level extends Model
{
    protected static $table = 'levels';
    protected static $fields = ['id', 'quest', 'answer', 'orders', 'difficulty', 'created_at', 'updated_at'];

    public function __construct()
    {
    }

    public function check_level(string $text): bool
    {
        if ($text == $this->answer) {
            return true;
        }
        return false;
    }
}
