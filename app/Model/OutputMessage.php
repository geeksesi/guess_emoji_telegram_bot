<?php
namespace App\Model;

use App\Enums\OutputMessageEnum;
use PDO;

final class OutputMessage extends Model
{
    protected static $table = "output_messages";
    protected static $fields = [
        "id" => PDO::PARAM_INT,
        "text" => PDO::PARAM_STR,
        "type" => PDO::PARAM_INT,
        "created_at" => PDO::PARAM_STR,
    ];

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

    public static function by_type(OutputMessageEnum $type)
    {
        $t = $type->value;
        return self::get_first("WHERE type=:type", [":type" => $t], "ORDER BY id DESC");
    }
}
