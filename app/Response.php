<?php

namespace App;

class Response
{
    protected $content;
    protected $statusCode;
    protected $headers = [];

    // public function __construct($content = '', $statusCode = 200)
    // {
    //     $this->content = $content;
    //     $this->statusCode = $statusCode;
    // }

    public function __construct()
    {
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function setContentJson($data)
    {
        $this->content = json_encode($data);
        $this->setHeader('Content-Type', 'application/json');
    }

    public function setContentView($viewName, $data = [])
    {
        ob_start();
        extract($data);
        include $viewName;
        $this->content = ob_get_clean();
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function send()
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
        echo $this->content;
    }

    public function redirect($url, $statusCode = 302)
    {
        header('Location: ' . $url, true, $statusCode);
        exit;
    }
}
