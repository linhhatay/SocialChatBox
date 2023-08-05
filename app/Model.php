<?php

declare(strict_types=1);

namespace App;

abstract class Model
{
    protected DB $db;

    public function __construct()
    {
        $this->db = App::db();
    }

    protected function query(string $query, array $params = [])
    {
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            die("Query execution failed: " . $e->getMessage());
        }
    }
}
