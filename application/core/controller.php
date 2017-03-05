<?php

class Controller
{
    public $db = null;
    public $model = null;

    function __construct($model = null)
    {
        $this->openDatabaseConnection();
        $this->loadModel($model);
    }

    private function openDatabaseConnection()
    {
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        $this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, $options);
    }

    public function loadModel($model)
    {
		if($model !== null)
		{
			$model_name = ucfirst($model).'_Model';    
			require APP . 'model/'.$model.'.php';
			$this->model = new $model_name($this->db);
		}
    }
}
