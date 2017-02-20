<?php
class Home extends Controller
{
    public function index()
    {	
		$areas = $this->model->getAreas();

		require APP . 'view/_templates/header.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer.php';
    }
	
	public function editRoom($id)
	{
		$room = $this->model->getRoom($id);

		require APP . 'view/home/edit_room.php';
	}
	
	public function editArea($id)
	{
		$area = $this->model->getArea($id);
		$area->rooms = $this->model->getAreaRooms($id);
		
		echo '<pre>';
		print_r($area);
		echo '</pre>';
		die();

		require APP . 'view/home/edit_area.php';
	}
}
