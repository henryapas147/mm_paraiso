<?php

namespace App\Controllers;

class PropertiesController extends Controller
{
    public function index()
    {
        $this->view('properties-rooms/properties');
    }

    public function properties()
    {
        $this->view('properties-rooms/properties');
    }

    public function rooms()
    {
        $this->view('properties-rooms/rooms');
    }

    public function roomCategories()
    {
        $this->view('properties-rooms/room_categories');
    }

    public function amenities()
    {
        $this->view('properties-rooms/amenities');
    }

    public function roomBlocks()
    {
        $this->view('properties-rooms/room_blocks');
    }

    public function diningTables()
    {
        $this->view('properties-rooms/dining_tables');
    }
}
