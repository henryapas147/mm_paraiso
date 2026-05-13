<?php

namespace App\Controllers;

class CustomerController extends Controller
{
    public function index()
    {
        $this->view('customers/customers_list');
    }

    public function customersList()
    {
        $this->view('customers/customers_list');
    }
}
