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
    private $session;

    public function __construct(
        private User $userModel,
        private Request $request,
        private Response $response,
        private EmailService $emailService
    ) {
        $this->session = Session::getInstance();
    }

    public function showSignup()
    {
        if ($this->session->has('user')) {
            header("Location: " . _WEB_ROOT . "/home");
            exit();
        }
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
        if ($this->session->has('user')) {
            header("Location: " . _WEB_ROOT . "/home");
            exit();
        }
        return View::make('login');
    }

    public function login()
    {

        $data = $this->request->all();
        $email = $this->request->input('email');
        $password = $this->request->input('password');

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $validator = new Validator($data, $rules);
        $session = Session::getInstance();
        $session->flashInput($data);

        if ($validator->validate()) {
            try {
                $user =  $this->userModel->login($email, $password);
                $session->set('user', $user);
                $this->response->redirect(_WEB_ROOT . '/home');

                // return View::make('index', ['user' => $user]);
            } catch (\Exception $e) {
                $errors = ['login' => [$e->getMessage()]];
                return View::make('login', ['errors' => $errors]);
            }
        } else {
            $errors = $validator->errors();
            return View::make('login', ['errors' =>  $errors]);
        }
    }

    public function logout()
    {
        $session = Session::getInstance();
        $uniqueId = $session->get('unique_id');

        $isLogout = $this->userModel->logout($uniqueId);

        if ($isLogout) {
            $session->destroy();
            return $this->response->redirect(_WEB_ROOT . '/login');
        }

        return $this->response->redirect(_WEB_ROOT);
    }

    public function showForgotPassword()
    {
        if ($this->session->has('user')) {
            header("Location: " . _WEB_ROOT . "/home");
            exit();
        }
        return View::make('forgotPassword');
    }

    public function forgotPassword()
    {
        $data = $this->request->all();
        $email = $this->request->input('email');

        $rules = [
            'email' => 'required|email',
        ];

        $validator = new Validator($data, $rules);
        $session = Session::getInstance();
        $session->flashInput($data);

        if ($validator->validate()) {
            try {
                $user =  $this->userModel->getUserByEmail($email);
                if (!$user) return;

                $token = bin2hex(random_bytes(16));
                $tokenHash = hash("sha256", $token);
                $expires = date("Y-m-d H:i:s", time() + 60 * 10);
                $stmt = $this->userModel->forgotPassword($tokenHash, $expires, $user['email']);
                if ($stmt) {
                    $resetLink = "http://localhost/Chatbox/reset-password/" . $token;
                    $this->emailService->sendEmail(
                        $user['email'],
                        $user['lname'],
                        'Notice of successful account forgot password',
                        'Click ' . $resetLink . ' to reset password.'
                    );
                }

                $this->response->redirect(_WEB_ROOT . '/forgot-password-success');
            } catch (\Exception $e) {
                $errors = ['login' => [$e->getMessage()]];
                return View::make('login', ['errors' => $errors]);
            }
        } else {
            $errors = $validator->errors();
            return View::make('login', ['errors' =>  $errors]);
        }
    }

    public function showForgotPasswordSuccess()
    {
        return View::make('forgotPasswordSuccess');
    }

    public function showFormResetPassword($token)
    {
        return View::make('resetPassword', ['token' => $token]);
    }

    public function resetPassword($token)
    {
        $tokenHash = hash('sha256', $token);
        $data = $this->request->all();
        $password = $this->request->input('password');
        $passwordConfirm = $this->request->input('passwordConfirm');

        $rules = [
            'password' => 'required|min:6',
            'passwordConfirm' => 'required|min:6',
        ];
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $validator = new Validator($data, $rules);

        if ($validator->validate()) {
            if ($password !== $passwordConfirm) {
                echo 'The re-entered password does not match';
                return;
            }
            try {
                $result = $this->userModel->resetPassword($tokenHash, $passwordHash);
                if ($result) {
                    $this->response->redirect(_WEB_ROOT . '/login');
                }
            } catch (\Exception $e) {
                $errors = ['resetPassword' => [$e->getMessage()]];
                return View::make('resetPassword', ['errors' => $errors]);
            }
        } else {
            $errors = $validator->errors();
            return View::make('resetPassword', ['errors' =>  $errors]);
        }
    }
}
