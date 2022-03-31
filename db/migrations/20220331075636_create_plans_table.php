<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePlansTable extends AbstractMigration
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
        $table = $this->table("plans", ["collation" => "utf8mb4_unicode_ci"]);
        $table
            ->addColumn("name", "string")
            ->addColumn("description", "text")
            ->addColumn("credit", "integer")
            ->addColumn("cost", "integer")
            ->addColumn("status", "integer", ["default" => 1])
            ->addColumn("created_at", "timestamp", [
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->addColumn("updated_at", "timestamp", [
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->addIndex(["name"], ["unique" => true])
            ->create();
    }
}
