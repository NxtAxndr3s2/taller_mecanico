<?php

class AuthController extends Controller
{
    public function login(): void
    {
        $this->view('auth/login');
    }

    public function authenticate(): void
    {
        $user = config('auth');

        $username = trim($_POST['username'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        if ($username === ($user['username'] ?? '') && password_verify($password, (string)($user['password_hash'] ?? ''))) {
            auth_login([
                'username' => $user['username'],
                'name' => $user['name'],
            ]);
            redirect('/dashboard');
        }

        flash_set('error', 'Credenciales incorrectas.');
        redirect('/login');
    }

    public function logout(): void
    {
        auth_logout();
        redirect('/login');
    }
}
