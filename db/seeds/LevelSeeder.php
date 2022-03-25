<?php

use Phinx\Seed\AbstractSeed;

class LevelSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $level = $this->fetchRow("SELECT * FROM levels ORDER BY orders DESC") ?? ["orders" => 0];

        $faker = Faker\Factory::create();
        $data = [];
        $orders = $level["orders"];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                "quest" => $faker->text(20),
                "answer" => $faker->text(20),
                "orders" => ++$orders,
                "difficulty" => round($orders / 10, 0),
            ];
        }

        $this->table("levels")
            ->insert($data)
            ->saveData();
    }
}
