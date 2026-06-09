<?php

namespace App\Controllers;

class ReportsController extends Controller
{
    public function index()
    {
        $this->view('reports/dashboard');
    }

    public function revenue()
    {
        $this->view('reports/revenue');
    }

    public function occupancy()
    {
        $this->view('reports/occupancy');
    }

    public function inventory()
    {
        $this->view('reports/inventory');
    }

    public function customers()
    {
        $this->view('reports/customers');
    }

    public function foodCanteen()
    {
        $this->view('reports/food_canteen');
    }

    public function financial()
    {
        $this->view('reports/financial');
    }

    public function posSales()
    {
        $this->view('reports/pos_sales');
    }
}
