<?php

class Controller
{
    public $model = null;

    function __construct()
    {
        $this->loadModel();
    }

    public function loadModel()
    {
        require APP . 'model/model.php';
        $this->model = new Model();
    }
}
