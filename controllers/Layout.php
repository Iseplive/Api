<?php

class Layout_Controller extends Controller {
	
	        /*public function __beforeAction(){
		
		// User authentication
		$user_model = new Sign_Model();
		Sign_Model::$auth_status = Sign_Model::AUTH_STATUS_NOT_LOGGED;
		
		// Authentication by post
		if(isset($_POST['username']) && isset($_POST['password'])){
			$username = $_POST['username'];
			$password = $_POST['password'];
			try {
				if(!preg_match('#^[a-z0-9-]+$#', $username))
					throw new Exception('Invalid username');
				if($user_model->authenticate($username, $password)){
					Sign_Model::$auth_status = Sign_Model::AUTH_STATUS_LOGGED;
					// Write session and cookie to remember sign-in
					Cookie::write('login', Encryption::encode($username.':'.$password), 60*24*3600);
					Session::write('username', $username);
					
				}else{
					throw new Exception('Bad username or password');
				}
				
			}catch(Exception $e){
				Sign_Model::$auth_status = Sign_Model::AUTH_STATUS_BAD_USERNAME_OR_PASSWORD;
				Cookie::delete('login');
				Session::delete('username');
			}
		
		}else{
			
			// Authentication by session
			if(($username = Session::read('username')) !== null){
				try {
					$user_model->loadUser($username);
					Sign_Model::$auth_status = Sign_Model::AUTH_STATUS_LOGGED;
				}catch(Exception $e){
					Session::delete('username');
					Cookie::delete('login');
				}
			
			// Authentication by cookies
			}else if(($login = Cookie::read('login')) !== null){
				try {
					if(isset($login) && $login = Encryption::decode($login)){
						$login = explode(':', $login);
						$username = $login[0];
						if(!preg_match('#^[a-z0-9-]+$#', $username))
							throw new Exception('Invalid username');
						array_splice($login, 0, 1);
						$password = implode(':', $login);
						if($user_model->authenticate($username, $password)){
							Sign_Model::$auth_status = Sign_Model::AUTH_STATUS_LOGGED;
							// Write session to remember sign-in
							Session::write('username', $username);
						}else{
							throw new Exception('Bad username or password');
						}
					}else{
						throw new Exception('Invalid user cookie');
					}
				}catch(Exception $e){
					Cookie::delete('login');
				}
			}
			
		}
		
	}*/
	
	public function index(){

	}

	function render(){
	}
  // JSON mode
	public function json(){
		header('Content-Type: application/json; charset=utf-8');
	}


}
