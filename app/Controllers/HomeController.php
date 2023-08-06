<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Response;
use App\Session;
use App\View;

class HomeController
{

    public function __construct(private Response $response)
    {
    }
    // GET /photos/{photo}/comments
    public function index(): View
    {
        // $session = Session::getInstance();
        // if ($session->get('unique_id')) {
        //     return $this->response->redirect(_WEB_ROOT);
        // }
        return View::make('signup');
    }

    public function home(): View
    {
        // $session = Session::getInstance();
        // if ($session->get('unique_id')) {
        //     return $this->response->redirect(_WEB_ROOT);
        // }
        return View::make('index');
    }

    // GET /photos/{photo}/comments/create
    public function create(): View
    {
        return View::make('/');
    }

    // POST /photos/{photo}/comments
    public function store()
    {
    }

    // GET /comments/{comment}
    public function show()
    {
    }

    // GET /comments/{comment}/edit
    public function edit()
    {
    }

    // PUT/PATCH /comments/{comment}
    public function update()
    {
    }

    // DELETE /comments/{comment}
    public function destroy()
    {
    }
}
