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

    public function dashboard()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/sign-in');
            exit;
        }
        if (($_SESSION['role_code'] ?? '') !== 'customer') {
            header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/');
            exit;
        }
        $this->viewCustomer('customers/dashboard');
    }

    public function reservations()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/sign-in');
            exit;
        }
        if (($_SESSION['role_code'] ?? '') !== 'customer') {
            header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/');
            exit;
        }
        $this->viewCustomer('customers/reservations');
    }

    public function payments()
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/sign-in');
            exit;
        }
        if (($_SESSION['role_code'] ?? '') !== 'customer') {
            header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/');
            exit;
        }
        $this->viewCustomer('customers/payments');
    }
}
