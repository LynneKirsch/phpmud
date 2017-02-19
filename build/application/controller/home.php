<?php
class Home extends Controller
{
    public function index()
    {	
		$rooms = $this->model->getRooms();

        require APP . 'view/_templates/header.php';
        require APP . 'view/home/index.php';
        require APP . 'view/_templates/footer.php';
    }
	
	public function editRoom($id)
	{
		$room = $this->model->getRoom($id);
		require APP . 'view/_templates/header.php';
        require APP . 'view/home/edit_room.php';
        require APP . 'view/_templates/footer.php';
	}
}
