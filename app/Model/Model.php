<?php
namespace App\Model;

use PDO;

abstract class Model
{
    protected static PDO $db;
    protected static $table;
    protected static $fields;

    public static function connection()
    {
        if (isset(self::$db)) {
            return self::$db;
        }
        try {
            self::$db = new \PDO(
                "mysql:host={$_ENV["MYSQL_HOST"]}:{$_ENV["MYSQL_PORT"]};dbname={$_ENV["MYSQL_DB"]}",
                $_ENV["MYSQL_USERNAME"],
                $_ENV["MYSQL_PASSWORD"],
                [
                    "charset" => "utf8mb4",
                    "collation" => "utf8_unicode_ci",
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
        return self::$db;
    }

    public static function create(array $parameter)
    {
        $table = static::$table;
        $db = self::connection();

        $keys = "";
        $bind_keys = "";
        $bind_params = [];
        foreach ($parameter as $key => $value) {
            if (in_array($key, static::$fields, true)) {
                $keys .= $key . ",";
                $bind_keys .= ":" . $key . ",";
                $bind_params[$key] = $value;
            }
        }
        $keys = rtrim($keys, ",");
        $bind_keys = rtrim($bind_keys, ",");

        // var_dump($keys, $bind_keys, $bind_params);
        die();
        $query = $db->prepare("INSERT INTO {$table} ({$keys}) VALUES ({$bind_keys})");
        if (!$query->execute($bind_params)) {
            throw new \Exception("Cannot Store model : " . get_class(), 1);
        }
        $id = $db->lastInsertId();
        return self::find($id);
    }

    public static function update(array $parameter, int $_id): bool
    {
        $table = static::$table;
        $db = self::connection();

        $states = "";
        $bind_params = [
            "id" => $_id,
        ];
        foreach ($parameter as $key => $value) {
            if (in_array($key, static::$fields, true)) {
                $states .= $key . "=:" . $key;
                $states .= ", ";
                $bind_params[$key] = $value;
            }
        }
        rtrim($states, ", ");

        $query = $db->prepare("UPDATE {$table} SET {$states} WHERE id=:id ");

        return (bool) $query->execute($bind_params);
    }

    public static function get_first(string $_where = "", array $_params = [], string $_order = "order by id asc")
    {
        $table = static::$table;
        $db = self::connection();

        $query = $db->prepare("SELECT * from {$table} {$_where} {$_order} LIMIT 1");

        if (!$query->execute($_params)) {
            return false;
        }
        $query->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $query->fetch(PDO::FETCH_CLASS);
    }

    public static function get_paginate(
        string $_where = "",
        array $_params = [],
        string $_order = "order by id asc",
        int $_page = 1,
        int $_per_page = 15
    ): array {
        $table = static::$table;
        $db = self::connection();

        $offset = ($_page - 1) * $_per_page;
        // $_params["per_page"] = $_per_page;
        // $_params["offset"] = $offset;

        $query = $db->prepare("SELECT * from {$table} {$_where} {$_order} LIMIT :offset, :per_page");
        $query->bindParam("offset", $offset, PDO::PARAM_INT);
        $query->bindParam("per_page", $_per_page, PDO::PARAM_INT);
        foreach ($_params as $key => $value) {
            $query->bindParam($key, $value);
        }

        if (!$query->execute()) {
            return false;
        }
        return $query->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function get_all(string $_where = "", array $_params = [], string $_order = "order by id asc")
    {
        $table = static::$table;
        $db = self::connection();

        $query = $db->prepare("SELECT * from {$table} {$_where} {$_order}");
        foreach ($_params as $key => $value) {
            $query->bindParam($key, $value);
        }

        if (!$query->execute()) {
            return false;
        }
        return $query->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function find(int $_id)
    {
        $table = static::$table;
        $db = self::connection();

        $query = $db->prepare("SELECT * from {$table} WHERE id=:id LIMIT 1");

        if (!$query->execute(["id" => $_id])) {
            return false;
        }
        $query->setFetchMode(PDO::FETCH_CLASS, static::class);
        return $query->fetch(PDO::FETCH_CLASS);
    }

    public static function delete(int $_id)
    {
        $table = static::$table;
        $db = self::connection();

        $query = $db->prepare("DELETE FROM {$table} WHERE id=:id LIMIT 1");

        return $query->execute(["id" => $_id]);
    }

    public static function delete_query(string $_where = "", array $_params = [])
    {
        $table = static::$table;
        $db = self::connection();

        $query = $db->prepare("DELETE FROM {$table} {$_where}");

        return $query->execute($_params);
    }
}
