<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersTable extends AbstractMigration
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
        $users = $this->table("users");
        $users
            ->addColumn("chat_id", "string", [
                "limit" => 200,
                "null" => false,
            ])
            ->addColumn("credit", "integer", ["default" => 0])
            ->addColumn("level", "integer")
            ->addColumn("created_at", "timestamp", [
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->addColumn("updated_at", "timestamp", [
                "default" => "CURRENT_TIMESTAMP",
            ])
            ->addIndex(["chat_id"], ["unique" => true])
            ->create();
    }
}
