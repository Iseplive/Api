<?php

class Sign_Controller extends Controller {

	public function signin() {
    $data = json_decode(file_get_contents("php://input"));
		if ($this->model->authenticate($data->login, $data->password)) {
			echo json_encode(Sign_Model::$auth_data);
		} else {
			throw new Exception('BAD_USER', 400);
		}
	}

	/**
	 * Logout
	 */
	public function logout() {
		Cookie::delete('login');
		Session::delete('username');
	}

}
