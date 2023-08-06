<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;

class User extends Model
{
    public function signup(int $id, string $firstName, string $lastName, string $email, string $password, $image, string $status)
    {
        $stmt = $this->query(
            "INSERT INTO users(unique_id, fname, lname, email, password, img, status) VALUES(?, ?, ?, ?, ?, ?, ?)",
            [$id, $firstName, $lastName, $email, $password, $image, $status]
        );
        $userId = (int) $this->db->lastInsertId();

        if ($userId) {
            $stmt = $this->query("SELECT * FROM users WHERE email = ?", [$email]);
            $user = $stmt->fetch();

            return $user;
        }
    }

    public function login($username, $password)
    {
    }
}
