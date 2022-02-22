<?php
namespace App;

use SQLite3;

class Model
{
    private $db;

    public function __construct()
    {
        $this->db = new SQLite3(__DIR__ . '/../database.sqlite');
    }

    public function make_levels_table()
    {
        $this->db->query(
            'CREATE TABLE levels (id INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL, quest TEXT UNIQUE NOT NULL, answer TEXT NOT NULL)'
        );
    }

    public function make_users_table()
    {
        $this->db->query(
            'CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL, chat_id TEXT UNIQUE NOT NULL, level_id TEXT NOT NULL)'
        );
    }

    public function add_level(string $_question, string $_answer): bool
    {
        $query = $this->db->prepare(
            'INSERT INTO levels (quest,answer) VALUES (:quest, :answer)'
        );
        $query->bindValue(':quest', $_question, SQLITE3_TEXT);
        $query->bindValue(':answer', strtolower($_answer), SQLITE3_TEXT);

        return (bool) $query->execute();
    }

    public function get_first_level_id(): int
    {
        $query = $this->db->query(
            'SELECT * FROM levels order by id asc limit 1'
        );
        $row = $query->fetchArray(SQLITE3_ASSOC);
        return $row['id'];
    }

    public function add_user(string $_chat_id): bool
    {
        $level_id = $this->get_first_level_id();
        $query = $this->db->prepare(
            'INSERT INTO users (chat_id, level_id) VALUES (:chat_id, :level_id)'
        );
        $query->bindValue(':chat_id', $_chat_id, SQLITE3_TEXT);
        $query->bindValue(':level_id', $level_id, SQLITE3_INTEGER);

        return (bool) $query->execute();
    }

    public function levels(): array
    {
        $query = $this->db->query('SELECT * FROM levels');
        $output = [];
        while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
            $output[] = $row;
        }
        return $output;
    }

    public function get_user(string $_chat_id): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM users WHERE chat_id=:chat_id'
        );
        $query->bindValue(':chat_id', $_chat_id, SQLITE3_TEXT);
        $result = $query->execute();
        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return $row;
        }
        if (!$this->add_user($_chat_id)) {
            throw new \Exception("Can't create user");
        }
        return $this->get_user($_chat_id);
    }

    public function get_level(int $_id)
    {
        $query = $this->db->prepare('SELECT * FROM levels WHERE id=:id');
        $query->bindValue(':id', $_id, SQLITE3_INTEGER);

        $result = $query->execute();
        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            return $row;
        }
        return false;
    }

    public function next_level(string $_id, int $_level_id): bool
    {
        $query = $this->db->prepare(
            'UPDATE users SET level_id=:level_id WHERE id=:id'
        );
        $query->bindValue(':level_id', $_level_id, SQLITE3_INTEGER);
        $query->bindValue(':id', $_id, SQLITE3_TEXT);

        return (bool) $query->execute();
    }
}
