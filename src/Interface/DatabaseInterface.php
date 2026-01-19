<?php

namespace SellNow\Interface;

use PDO;

interface DatabaseInterface
{
    public function getConnection(): PDO;
    public function query(string $sql);
}
