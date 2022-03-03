<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLevelsTable extends AbstractMigration
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
        $table = $this->table('levels');
        $table
            ->addColumn('quest', 'string', [
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('answer', 'string', ['limit' => 500, 'null' => false])
            ->addColumn('order', 'integer', ['default' => 0])
            ->addColumn('difficulty', 'integer', ['default' => 1])
            ->addColumn('created_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addColumn('updated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
            ])
            ->addIndex(['quest'], ['unique' => true])
            ->create();
    }
}
