<?php

class Group_Controller extends Controller {
	/**
	 * List of the groups
	 */
	public function index($params){
		echo(json_encode($this->model->getAll()));
	}

	/**
	 * Show the profile of a group
	 */
	public function view($params){
		try {
      $group = $this->model->getInfoByName($params['group']);
      $group['description'] = Text::inHTML($group['description']);
			echo json_encode($group);
		}catch(Exception $e){
			throw new Exception(__('BAD_ASSOCE'), 404);
		}
	}
}
