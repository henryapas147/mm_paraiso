<?php

namespace App\Controllers;

class Controller
{
    protected $params = [];

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    protected function view($view, $data = [])
    {
        extract($data);
        
        $viewFile = '../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new \Exception("View file not found: $viewFile");
        }
    }

    protected function model($model)
    {
        $modelFile = '../models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        } else {
            throw new \Exception("Model file not found: $modelFile");
        }
    }
}
