<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLevelHintsTable extends AbstractMigration
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
        $table = $this->table('level_hints');
        $table
            ->addColumn('hint', 'text', ['null' => false])
            ->addColumn('order', 'integer', ['default' => 0])
            ->addColumn('level_id', 'integer')
            ->addForeignKey('level_id', 'levels', 'id', [
                'delete' => 'CASCADE',
                'update' => 'NO_ACTION',
            ])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->create();
    }
}
