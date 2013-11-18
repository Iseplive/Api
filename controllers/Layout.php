<?php

class Layout_Controller extends Controller {

    public function __beforeAction(){

      // User authentication
      $user_model = new User_Model();
      User_Model::$auth_status = User_Model::AUTH_STATUS_NOT_LOGGED;
      $route = Routes::getVars(preg_replace('#^'.preg_quote(Config::URL_ROOT).'#', '', urldecode($_SERVER['REQUEST_URI'])));
      //Authentication by session
			if(($username = Session::read('username')) !== null){
				try {
					$user_model->loadUser($username);
					User_Model::$auth_status = User_Model::AUTH_STATUS_LOGGED;
				}catch(Exception $e){
					Session::delete('username');
					Cookie::delete('mLogin');
				}
      } else if(isset($_COOKIE['mLogin'])){
				try {
          $login = $_COOKIE['mLogin'];
          
					if(isset($login) && $login = Encryption::decode($login)){
						$login = explode(':', $login);
						$username = $login[0];
						if(!preg_match('#^[a-z0-9-]+$#', $username))
							header('HTTP/1.1 401 Unauthorized');
              throw new Exception();
						array_splice($login, 0, 1);
						$password = implode(':', $login);
						if($user_model->authenticate($username, $password)){
							User_Model::$auth_status = User_Model::AUTH_STATUS_LOGGED;
              Cookie::write('mLogin', Encryption::encode($username.':'.$password), 1800);
							// Write session to remember sign-in
							Session::write('username', $username);
						}else{
							header('HTTP/1.1 401 Unauthorized');
              throw new Exception();
						}
					}else{
						header('HTTP/1.1 401 Unauthorized');
            throw new Exception();
					}
				}catch(Exception $e){
					Cookie::delete('mLogin');
				}
      } else if (!isset($_COOKIE['mLogin']) && $route["controller"] === "User" && $route["action"] === "signin") {
      } else {
        header('HTTP/1.1 401 Unauthorized');
        throw new Exception();
      }
    }

	public function index(){

	}

	function render(){
	}
  // JSON mode
	public function json(){
		header('Content-Type: application/json; charset=utf-8');
	}


}
