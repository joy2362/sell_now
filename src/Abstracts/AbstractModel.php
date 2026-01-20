<?php

namespace SellNow\Abstracts;

use Exception;
use PDO;
use SellNow\Interface\DatabaseInterface;

abstract class AbstractModel
{
    protected $table;

    public function __construct(private DatabaseInterface $db)
    {
        if (!$this->table) {
            throw new Exception("Table name not defined in " . get_class($this));
        }
    }

    // Get all rows
    public function all(): array
    {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find row by ID
    public function find(int $id): ?array
    {
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Find row by where query
    public function findWhere(array $conditions): ?array
    {
        $fields = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));

        $stmt = $this->db->getConnection()->prepare("SELECT * FROM {$this->table} WHERE $fields");

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Insert row
    public function create(array $data): bool
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $stmt = $this->db->getConnection()->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Update row by ID
    public function update(int $id, array $data): bool
    {
        $fields = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));

        $stmt = $this->db->getConnection()->prepare("UPDATE {$this->table} SET {$fields} WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Delete row by ID
    public function delete(int $id): bool
    {
        $stmt = $this->db->getConnection()->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Count rows
    public function count(): int
    {
        $stmt = $this->db->getConnection()->prepare("SELECT COUNT(*) as count FROM {$this->table}");
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    // Find rows with conditions
    public function where(array $conditions): array
    {
        $fields = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($conditions)));

        $stmt = $this->db->getConnection()->prepare("SELECT * FROM {$this->table} WHERE $fields");

        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
