<?php

class Media_Controller extends Controller {
	public function index(){
		$medias=$this->model->getMedias();
    echo json_encode($medias);
  }
}
?>
