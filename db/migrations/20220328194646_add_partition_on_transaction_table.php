<?php
declare(strict_types=1);

use App\Model\GameLog;
use App\Model\Transaction;
use Phinx\Migration\AbstractMigration;

final class AddPartitionOnTransactionTable extends AbstractMigration
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
        $table = $this->table("transactions", ["collation" => "utf8mb4_unicode_ci"]);
        $table->changePrimaryKey(["id", "user_id"])->update();

        $c = Transaction::connection();
        $res = $c->query("alter table transactions partition by HASH(user_id) PARTITIONS 10; ");
        var_dump($res);
    }
}
