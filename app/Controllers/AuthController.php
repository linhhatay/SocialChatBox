<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\Request;
use App\Response;
use App\Services\EmailService;
use App\Session;
use App\Validator;
use App\View;

class AuthController
{

    public function __construct(
        private User $userModel,
        private Request $request,
        private Response $response,
        private EmailService $emailService
    ) {
    }

    public function showSignup()
    {

        return View::make('signup');
    }

    public function signup()
    {
        $data = $this->request->all();
        $file = $this->request->file('image');
        $firstName = $this->request->input('fname');
        $lastName = $this->request->input('lname');
        $email = $this->request->input('email');
        $password = $this->request->input('password');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $rules = [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $validator = new Validator($data, $rules);
        $session = Session::getInstance();
        $session->flashInput($data);

        if ($validator->validate()) {
            try {
                $imagePath = null;
                $status = 'Active now';
                $randomId = rand(time(), 10000000);

                if ($file['name']) {
                    $targetDir = 'storage/uploads/img/users/';
                    $targetFile = $targetDir . basename($file["name"]);
                    $imagePath = $targetFile;

                    if (!move_uploaded_file($file["tmp_name"], $targetFile)) {
                        echo "Có lỗi xảy ra khi tải lên tệp.";
                        return;
                    }
                }

                $user = $this->userModel->signup($randomId, $firstName, $lastName, $email, $hashedPassword, $imagePath, $status);

                if ($user) {
                    $this->emailService->sendEmail(
                        $user['email'],
                        $user['fname'],
                        'Notice of successful account registration',
                        'We would like to inform you that you have successfully registered an account. Thank you for joining us!'
                    );

                    $session->set('unique_id', $user['unique_id']);
                    return View::make('index', ['user' => $user]);
                }
            } catch (\Exception $e) {
                $errors = ['signup' => [$e->getMessage()]];
                return View::make('signup', ['errors' => $errors]);
            }
        } else {
            $errors = $validator->errors();
            return View::make('signup', ['errors' =>  $errors]);
        }
    }

    public function showLogin()
    {
        return View::make('login');
    }

    public function login()
    {
        echo "Login...";
    }
}
