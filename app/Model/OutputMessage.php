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
        $t = $type->value;

        $table = static::$table;
        $db = self::connection();

        $ids_query = $db->prepare("SELECT id from {$table} WHERE type=:type");

        if (!$ids_query->execute([":type" => $t])) {
            throw new \Exception("Error on get " . $t . " ids for random", 500);
        }
        $ids_result = $ids_query->fetchAll(PDO::FETCH_ASSOC);
        $ids = array_column($ids_result, "id") ?? [];
        $id = $ids[array_rand($ids)] ?? null;

        if (is_null($id)) {
            return null;
        }

        return self::find($id);
    }

    public static function by_type(OutputMessageEnum $type)
    {
        $t = $type->value;
        return self::get_first("WHERE type=:type", [":type" => $t], "ORDER BY id DESC");
    }
}
