<?php

use Phinx\Seed\AbstractSeed;

class LevelHintsSeeder extends AbstractSeed
{
    public function getDependencies()
    {
        return ["LevelSeeder"];
    }

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
        $levels = $this->fetchAll("SELECT id FROM levels");

        $levels = array_column($levels, "id");

        $faker = Faker\Factory::create();
        $data = [];
        $orders = [];
        for ($i = 0; $i < 100; $i++) {
            $level = $levels[array_rand($levels, 1)];
            $order = $orders[$level] ?? 0;
            $orders[$level] = ++$order;
            $data[] = [
                "hint" => $faker->text(),
                "level_id" => $level,
                "orders" => $orders[$level],
                "type" => 1,
            ];
        }

        $this->table("level_hints")
            ->insert($data)
            ->saveData();
    }
}
