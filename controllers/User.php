<?php

class User_Controller extends Controller {

	public function signin() {
    $data = json_decode(file_get_contents("php://input"));

    $user_model = new User_Model();
		User_Model::$auth_status = User_Model::AUTH_STATUS_NOT_LOGGED;

    $username = $data->login;
		$password = $data->password;
    try {
      if (!preg_match('#^[a-z0-9-]+$#', $username)) {
        throw new Exception('BAD_USER', 400);
      }
      if($user_model->authenticate($username, $password)){
        User_Model::$auth_status = User_Model::AUTH_STATUS_LOGGED;
        // Write session and cookie to remember sign-in
        Cookie::write('mLogin', Encryption::encode($username.':'.$password), 1800);
        Session::write('username', $username);
        echo json_encode(array("cookie" => Encryption::encode($username.':'.$password), "username" => User_Model::$auth_data['username']));
      }else{
        throw new Exception('', 401);
      }

    }catch(Exception $e){
      User_Model::$auth_status = User_Model::AUTH_STATUS_BAD_USERNAME_OR_PASSWORD;
      Cookie::delete('mLogin');
      Session::delete('username');
    }
	}

	/**
	 * Logout
	 */
	public function logout() {
		Cookie::delete('mLogin');
    Cookie::delete('login');
		Session::delete('username');
	}
  
  /**
	 * Edit personnal information
	 */
	public function profile_edit($params){
		$this->setView('profile_edit.php');
		$this->setTitle(__('USER_EDIT_TITLE'));
		
		$is_logged = isset(User_Model::$auth_data);
		$is_student = $is_logged && isset(User_Model::$auth_data['student_number']);
		
		// Authorization
		if(!$is_student)
			throw new ActionException('Page', 'error404');
		
		$user = User_Model::$auth_data;
		
		// Birthday
		$user['birthday'] = date(__('USER_EDIT_FORM_BIRTHDAY_FORMAT'), strtotime($user['birthday']));
		
		// Saving data
		if(isset($_POST['mail']) && isset($_POST['msn']) && isset($_POST['jabber'])
		&& isset($_POST['address']) && isset($_POST['zipcode']) && isset($_POST['city'])
		&& isset($_POST['cellphone']) && isset($_POST['phone']) && isset($_POST['birthday'])){
			
			try {
				
				// Other info
				$data = array(
					'mail'		=> $_POST['mail'],
					'msn'		=> $_POST['msn'],
					'jabber'	=> $_POST['jabber'],
					'address'	=> $_POST['address'],
					'zipcode'	=> $_POST['zipcode'],
					'city'		=> $_POST['city'],
					'cellphone'	=> $_POST['cellphone'],
					'phone'		=> $_POST['phone'],
					'birthday'	=> $_POST['birthday']
				);
				
				$this->model->save((int) User_Model::$auth_data['id'], $data);
				Routes::redirect('student', array('username' => User_Model::$auth_data['username']));
				
			}catch(FormException $e){
				foreach($data as $key => $value)
					$user[$key] = $value;
				
				$this->set('form_error', $e->getError());
			}
		}
		
		$this->set('user', $user);
		$this->addJSCode('User.initEdit();');
		
	}
}
