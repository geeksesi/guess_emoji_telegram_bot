<?php
namespace App\Model;

final class LevelHint extends Model
{
    protected static $table = 'level_hints';
    protected static $fields = ['id', 'hint', 'level_id', 'orders', 'created_at', 'updated_at'];

    public function __construct()
    {
    }

    public function level()
    {
        Level::get_first('WHERE id=:id', ["id" => $this->level_id]);
    }
}
