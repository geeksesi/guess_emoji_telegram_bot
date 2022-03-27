<?php
declare(strict_types=1);

use App\Model\Level;
use Phinx\Migration\AbstractMigration;

final class RefactorUserTable extends AbstractMigration
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
        $users = $this->table("users", ["collation" => "utf8mb4_unicode_ci"]);
        $users->changeColumn("level", "integer", ["null" => true])->update();

        $c = Level::connection();
        $res = $c->prepare("UPDATE users SET level=(SELECT id FROM levels where orders=level);");
        var_dump($res->execute());

        $users->renameColumn("level", "level_id")->update();
    }
}
