<?php

class Model
{
    private $db;

    public function __construct()
    {
        $this->db = new SQLite3('db.sqlite');
    }

    public function migrate()
    {
        // levels table
        $this->db->query(
            'CREATE TABLE levels (ID INT PRIMARY KEY NOT NULL, question TEXT NOT NULL, answer TEXT NOT NULL)'
        );
        // Users table
        $this->db->query(
            'CREATE TABLE users (
                ID INT PRIMARY KEY NOT NULL, 
                user_id TEXT NOT NULL,
                level_id INT,
            )'
        );
    }

    /**
     * Undocumented function
     *
     * @param string $chat_id
     * @param string $answer
     * @return boolean
     */
    public function check_answer(string $chat_id, string $answer): array|bool
    {
        $user_query = $this->db->prepare(
            'SELECT * FROM users WHERE user_id=:user_id LIMIT 1'
        );
        $user_query->bindValue(':user_id', $chat_id, SQLITE3_TEXT);
        $user = $user_query->execute();
        $user = $user->fetchArray(SQLITE3_ASSOC);

        $level_id = $user['level_id'];

        $level_query = $this->db->prepare(
            'SELECT * FROM levels WHERE ID=:id AND  answer LIKE :answer LIMIT 1'
        );
        $level_query->bindValue(':id', $level_id, SQLITE3_INTEGER);
        $level_query->bindValue(':answer', $answer, SQLITE3_TEXT);
        $level = $level_query->execute();
        while ($level->fetchArray(SQLITE3_ASSOC)) {
            return $this->lvl_up($user["ID"], $level["ID"]);
        }
        return false;
    }

    public function lvl_up(int $user_id, int $current_level): array
    {
        # code...
    }
}
