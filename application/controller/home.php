<?php
class Home extends Controller
{
    public function index()
    {
		$text = $this->model->helloWorld();
        require APP . 'view/_templates/header.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer.php';
	}
}
