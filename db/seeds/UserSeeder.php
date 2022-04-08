<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
        $faker = Faker\Factory::create();
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                "chat_id" => $faker->text(),
                "credit" => rand(50, 500),
                "level_id" => rand(0, 100),
                "invite_key" => uniqid(),
            ];
        }

        $this->table("users")
            ->insert($data)
            ->saveData();
    }
}
