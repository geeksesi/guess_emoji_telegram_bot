<?php

use Phinx\Seed\AbstractSeed;
use App\Enums\OutputMessageEnum;

class OutPutMessagesSeeder extends AbstractSeed
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
        $types = OutputMessageEnum::cases();

        $data = [];

        foreach ($types as $type) {
            $data[] = [
                "text" => "OUTPUT MESSAGE :: " . $type->name,
                "type" => $type->value,
            ];
        }

        $this->table("output_messages")
            ->insert($data)
            ->saveData();
    }
}
