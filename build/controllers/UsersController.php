<?php

namespace App\Controllers;

class UsersController extends Controller
{
    public function index()
    {
        $this->view('users/users_list');
    }

    public function create()
    {
        $this->view('users/users_create');
    }

    public function edit()
    {
        $this->view('users/users_edit');
    }
}
