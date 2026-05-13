<?php

namespace App\Controllers;

class AuthController extends Controller
{
    public function signIn()
    {
        $this->viewRaw('auth/sign-in');
    }

    public function signUp()
    {
        $this->viewRaw('auth/sign-up');
    }

    public function logout()
    {
        $this->viewRaw('auth/logout');
    }

    public function profile()
    {
        $this->view('auth/profile');
    }

    public function accountSettings()
    {
        $this->view('auth/account-settings');
    }
}
