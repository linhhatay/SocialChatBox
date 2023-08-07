<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;
use App\Session;

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

    public function login($email, $password)
    {
        $stmt = $this->query("SELECT * FROM users WHERE email = ?", [$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            throw new \Exception('Địa chỉ email không xác định. Kiểm tra lại hoặc thử tên người dùng của bạn.');
        }

        $session = Session::getInstance();
        $session->set('unique_id', $user['unique_id']);
        return $user;
    }

    public function logout($uniqueId)
    {
        $status = 'Offline now';

        $stmt = $this->query("UPDATE users SET status = ? WHERE unique_id = ?", [$status, $uniqueId]);

        if ($stmt) {
            return true;
        }

        return false;
    }
}
