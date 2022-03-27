<?php
declare(strict_types=1);

use App\Model\GameLog;
use Phinx\Migration\AbstractMigration;

final class RefactorGameLogTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $current_data = GameLog::get_all();
        $new_data = [];
        foreach ($current_data as $gameLog) {
            $new_data[$gameLog->level_id][$gameLog->user_id] = [
                "level_id" => $gameLog->level_id,
                "user_id" => $gameLog->user_id,
                "try" => $new_data[$gameLog->level_id][$gameLog->user_id]["try"] ?? 1,
                "hint_count" =>
                    $gameLog->hint_count > ($new_data[$gameLog->level_id][$gameLog->user_id]["hint_count"] ?? 0)
                        ? $gameLog->hint_count
                        : $new_data[$gameLog->level_id][$gameLog->user_id]["hint_count"] ?? 0,
                "balance" => 0,
            ];
        }

        $table = $this->table("game_logs", ["collation" => "utf8mb4_unicode_ci"]);
        $table->truncate();
        $table
            ->addColumn("try", "integer")
            ->addColumn("balance", "integer")
            ->update();
        foreach ($new_data as $users) {
            foreach ($users as $log) {
                GameLog::create($log);
            }
        }
    }
}
