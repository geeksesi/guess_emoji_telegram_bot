<?php
namespace App;

use SQLite3;

class Model
{
    private $db;

    public function __construct()
    {
        try {
            $this->db = new \PDO(
                "mysql:host={$_ENV['MYSQL_HOST']}:{$_ENV['MYSQL_PORT']};dbname={$_ENV['MYSQL_DB']}",
                $_ENV['MYSQL_USERNAME'],
                $_ENV['MYSQL_PASSWORD']
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function add_level(string $_question, string $_answer): bool
    {
        $query = $this->db->prepare(
            'INSERT INTO levels (quest,answer) VALUES (:quest, :answer)'
        );
        $query->bindParam(':quest', $_question);
        $query->bindParam(':answer', strtolower($_answer));
        $query->bindParam(':answer', strtolower($_answer));
        $query->bindParam(':answer', strtolower($_answer));

        return (bool) $query->execute();
    }

    public function get_first_level_id(): int
    {
        $query = $this->db->query(
            'SELECT * FROM levels order by orders asc limit 1',
            \PDO::FETCH_ASSOC
        );
        $row = $query->fetchAll()[0] ?? null;
        if (is_array($row)) {
            return $row['id'];
        }
        return 0;
    }

    public function add_user(string $_chat_id): bool
    {
        $level_id = $this->get_first_level_id();
        $query = $this->db->prepare(
            'INSERT INTO users (chat_id, level_id) VALUES (:chat_id, :level_id)'
        );
        $query->bindParam(':chat_id', $_chat_id);
        $query->bindParam(':level_id', $level_id);

        return (bool) $query->execute();
    }

    public function levels(): array
    {
        $query = $this->db->query('SELECT * FROM levels', \PDO::FETCH_ASSOC);
        $output = [];
        while ($row = $query->fetchAll()) {
            $output[] = $row;
        }
        return $output;
    }

    public function get_user(string $_chat_id): array
    {
        $query = $this->db->prepare(
            'SELECT * FROM users WHERE chat_id=:chat_id'
        );
        $query->bindParam(':chat_id', $_chat_id);
        $query->execute();
        $query->setFetchMode(\PDO::FETCH_ASSOC);
        $row = $query->fetchAll()[0] ?? null;
        if (is_array($row)) {
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
        $query->bindParam(':id', $_id);

        $query->execute();
        $query->setFetchMode(\PDO::FETCH_ASSOC);

        $row = $query->fetchAll() ?? null;
        if (is_array($row)) {
            return $row;
        }
        return false;
    }

    public function next_level(string $_id, int $_level_id): bool
    {
        $query = $this->db->prepare(
            'UPDATE users SET level_id=:level_id WHERE id=:id'
        );
        $query->bindParam(':level_id', $_level_id);
        $query->bindParam(':id', $_id);

        return $query->execute();
    }
}
