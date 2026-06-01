<?php

namespace App\Controllers;

class FoodCanteenController extends Controller
{
    public function index()
    {
        $this->view('food-canteen/food_categories');
    }

    public function foodCategories()
    {
        $this->view('food-canteen/food_categories');
    }

    public function menuItems()
    {
        $this->view('food-canteen/menu_items');
    }

    public function recipes()
    {
        $this->view('food-canteen/recipes');
    }

    public function foodOrders()
    {
        $this->view('food-canteen/food_orders');
    }
}
