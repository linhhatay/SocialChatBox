<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Request;

class UserController
{
    public function __construct(
        private User $userModel,
        private Request $request
    ) {
    }

    public function getAll()
    {
        $users = $this->userModel->getAll();

        if (count($users) === 1) {
            return json_encode('No users are available to chat');
        }

        return json_encode($users);
    }

    public function searchUser()
    {
        $keyword = (string) $this->request->post('key');
        $users = $this->userModel->search($keyword);

        if (count($users) <= 0) {
            return json_encode('No user found related to your search term');
        }

        return json_encode($users);
    }
}
