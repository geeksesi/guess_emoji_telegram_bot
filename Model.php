<?php

class Model
{
    private $db;

    public function __construct()
    {
        $this->db = new SQLite3(__DIR__ . '/db.sqlite');
    }

    public function migration()
    {
        $this->db->query(
            'CREATE TABLE levels (ID INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, question TEXT NOT NULL UNIQUE, answer TEXT NOT NULL)'
        );

        /**
         *  current_status
         * 0 - level
         * 1 - add question
         */

        $this->db->query(
            'CREATE TABLE users (ID INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, chat_id TEXT NOT NULL UNIQUE, level_id INT DEFAULT 1, current_status INTEGER DEFAULT 0)'
        );
    }

    public function add_level(string $question, string $answer): bool
    {
        $query = $this->db->prepare(
            'INSERT INTO levels (question,answer) VALUES (:question, :answer)'
        );
        $query->bindValue(':question', $question, SQLITE3_TEXT);
        $query->bindValue(':answer', $answer, SQLITE3_TEXT);
        $result = $query->execute();
        return (bool) $result;
    }

    public function add_user(string $chat_id): bool
    {
        $query = $this->db->prepare(
            'INSERT INTO users (chat_id) VALUES (:chat_id)'
        );
        $query->bindValue(':chat_id', $chat_id, SQLITE3_TEXT);
        $result = $query->execute();
        return (bool) $result;
    }

    public function get_user_level(string $chat_id): int
    {
        $query = $this->db->prepare(
            'SELECT level_id FROM users WHERE chat_id=:chat_id'
        );
        $query->bindValue(':chat_id', $chat_id, SQLITE3_TEXT);
        $result = $query->execute();
        return $result->fetchArray(SQLITE3_ASSOC)[0]['level_id'];
    }

    public function check_answer(string $chat_id, string $text): bool
    {
        $query = $this->db->prepare(
            'SELECT * FROM user WHERE chat_id=:chat_id AND level_id=(SELECT ID FROM levels WHERE answer like :answer)'
        );
        $query->bindValue(':chat_id', $chat_id, SQLITE3_TEXT);
        $query->bindValue(':answer', $text, SQLITE3_TEXT);
        $result = $query->execute();
        if ($result->numColumns()) {
            return true;
        }
        return false;
    }

    public function update_user_status(string $chat_id, int $status)
    {
        $query = $this->db->prepare(
            'UPDATE users SET current_status=:status WHERE  chat_id=:chat_id'
        );
        $query->bindValue(':current_status', $status, SQLITE3_INTEGER);
        $query->bindValue(':chat_id', $chat_id, SQLITE3_TEXT);
        $result = $query->execute();
        return (bool) $result;
    }
}
