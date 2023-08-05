<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;

class HomeController
{

    public function __construct()
    {
    }
    // GET /photos/{photo}/comments
    public function index(): View
    {
        return View::make('login');
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
