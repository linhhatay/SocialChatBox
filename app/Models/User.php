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

        $status = 'Active now';

        $stmt = $this->query("UPDATE users SET status = ? WHERE unique_id = ?", [$status, $user['unique_id']]);

        if ($stmt) {
            $stmt = $this->query("SELECT * FROM users WHERE email = ?", [$email]);
            $user = $stmt->fetch();
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

    public function get($uniqueId)
    {
        $stmt = $this->query("SELECT * FROM users WHERE unique_id = ?", [$uniqueId]);
        $users = $stmt->fetch();

        return $users;
    }

    public function getAll($uniqueId)
    {
        $stmt = $this->query("SELECT * FROM users WHERE NOT unique_id = ?", [$uniqueId]);
        $users = $stmt->fetchAll();

        return $users;
    }

    public function search(string $keyword, $uniqueId)
    {
        $stmt = $this->query("SELECT * FROM users WHERE NOT unique_id = $uniqueId AND (fname LIKE '%$keyword%' OR lname LIKE '%$keyword%')");
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getUserByEmail(string $email)
    {
        $stmt = $this->query("SELECT * FROM users WHERE email = ?", [$email]);
        $result = $stmt->fetch();
        return $result;
    }

    public function forgotPassword(string $resetToken, string $resetExpires, string $email)
    {
        $stmt = $this->query("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE email = ?", [$resetToken, $resetExpires, $email]);
        return $stmt;
    }

    public function resetPassword(string $resetToken, string $password)
    {
        $stmt = $this->query("SELECT * FROM users WHERE password_reset_token = ?", [$resetToken]);
        $user = $stmt->fetch();
        if (!$user)  return 'Token not found';

        if (strtotime($user['password_reset_expires']) <= time()) return 'Token has expired';

        $stmt = $this->query("UPDATE users SET password = ?, password_reset_token = null, password_reset_expires = null WHERE email = ?", [$password, $user['email']]);
        return true;
    }
}
