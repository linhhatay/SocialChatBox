<?php

namespace App;

class Session
{
    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            session_start();
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function flash($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function getFlash($key, $default = null)
    {
        $value = self::get($key, $default);
        self::remove($key);
        return $value;
    }

    public function all()
    {
        return $_SESSION;
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
        self::$instance = null;
    }

    public function flashInput($data)
    {
        $_SESSION['_old_input'] = $data;
    }

    public function old($key, $default = null)
    {
        if (isset($_SESSION['_old_input'][$key])) {
            $value = $_SESSION['_old_input'][$key];
            unset($_SESSION['_old_input'][$key]);
            return $value;
        }
        return $default;
    }
}
