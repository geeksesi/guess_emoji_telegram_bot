<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePaymentTable extends AbstractMigration
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
        $table = $this->table("payments", ["collation" => "utf8mb4_unicode_ci"]);
        $table
            ->addColumn("user_id", "integer", ["null" => true])
            ->addForeignKey("user_id", "users", "id", [
                "delete" => "SET NULL",
                "update" => "NO_ACTION",
            ])
            ->addColumn("plan_id", "integer", ["null" => true])
            ->addForeignKey("plan_id", "plans", "id", [
                "delete" => "SET NULL",
                "update" => "NO_ACTION",
            ])
            ->addColumn("payment_key", "string", ["limit" => 500, "null" => true])
            ->addColumn("credit", "integer")
            ->addColumn("cost", "integer")
            ->addColumn("status", "integer", ["default" => 1])
            ->addColumn("created_at", "timestamp", [
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->addColumn("updated_at", "timestamp", [
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->addIndex(["payment_key"], ["unique" => true])

            ->create();
    }
}
