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
        $faker = Faker\Factory::create();
        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                "quest" => $faker->text(20),
                "answer" => $faker->text(20),
                "difficulty" => rand(0, 100),
            ];
        }

        $this->table("levels")
            ->insert($data)
            ->saveData();
    }
}
