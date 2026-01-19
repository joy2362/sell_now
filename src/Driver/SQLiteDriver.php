<?php

namespace SellNow\Config;

use PDO;
use PDOException;
use SellNow\Interface\DatabaseInterface;

class SQLiteDriver implements DatabaseInterface
{
    private PDO $conn;

    public function __construct(array $config)
    {
        try {
            $this->conn = new PDO('sqlite:' . $config['path']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('SQLite Connection Error: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }

    public function query(string $sql)
    {
        return $this->conn->query($sql);
    }
}
