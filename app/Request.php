<?php

namespace App;

class Request
{
    protected $input = [];
    protected $method;
    protected $headers;

    public function __construct()
    {
        $this->input = $_REQUEST;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->headers = getallheaders();
    }

    public function input($key, $default = null)
    {
        return isset($this->input[$key]) ? $this->input[$key] : $default;
    }

    public function all()
    {
        return $this->input;
    }

    public function method()
    {
        return $this->method;
    }

    public function header($key, $default = null)
    {
        return isset($this->headers[$key]) ? $this->headers[$key] : $default;
    }

    public function has($key)
    {
        return isset($this->input[$key]);
    }

    public function query($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    public function post($key, $default = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    public function only(array $keys)
    {
        return array_intersect_key($this->input, array_flip($keys));
    }

    public function except(array $keys)
    {
        return array_diff_key($this->input, array_flip($keys));
    }

    public function ajax()
    {
        return (!empty($this->header('X-Requested-With')) && strtolower($this->header('X-Requested-With')) == 'xmlhttprequest');
    }

    public function isMethod($method)
    {
        return (strtoupper($method) === strtoupper($this->method));
    }

    public function is($pattern)
    {
        return fnmatch($pattern, $this->path(), FNM_CASEFOLD);
    }

    public function ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function file($key)
    {
        return isset($_FILES[$key]) ? $_FILES[$key] : null;
    }

    public function hasFile($key)
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE;
    }

    // Helper method to get request URI
    protected function path()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}