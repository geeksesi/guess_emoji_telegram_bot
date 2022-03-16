<?php
namespace App\Model;

use App\Enums\OutputMessageEnum;

final class OutputMessage extends Model
{
    protected static $table = "levels";
    protected static $fields = ["id", "text", "type", "created_at"];

    public function __construct()
    {
    }

    public static function random(OutputMessageEnum $type)
    {
        $table = self::$table;
        $t = $type->value;
        return self::get_first(
            "WHERE id >= (SELECT FLOOR( MAX(id) * RAND()) FROM {$table} WHERE type=:type",
            ["type" => $t],
            "ORDER BY id ASC"
        );
    }
}
