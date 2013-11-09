<?php

class Sign_Model extends Model {
	
	/**
	 * Associative array of authenticate user's data
	 * @var array
	 */
	public static $auth_data;
	
	/**
	 * Status of the user's authentication
	 * @var int
	 */
	public static $auth_status;
	
	/**
	 * Possible status of authentification
	 * @var int
	 */
	const AUTH_STATUS_NOT_LOGGED = 1;
	const AUTH_STATUS_LOGGED = 2;
	const AUTH_STATUS_BAD_USERNAME_OR_PASSWORD = 3;
	
	/**
	 * Verify the username and the password of an user, using the ISEP LDAP
	 *
	 * @param string $username	User name
	 * @param string $password	Password
	 * @return boolean	True on success, false on failure
	 */
	public function authenticate($username, $password){
		if(Config::DEBUG){
			$this->loadUser($username);
			return true;
		}
		error_reporting(E_ALL);
		try {
			if(Config::AUTHENTICATION_MODE == 'ldap'){
				$ldap_conn = ldap_connect('ldaps://'.Config::$LDAP['host']);//, Config::$LDAP['port']
				$result = ldap_bind($ldap_conn, 'uid='.$username.','.Config::$LDAP['basedn'], $password);
				
			}			
			// Login successful
			if($result){
				$this->loadUser($username);
			}
			return $result;
			
		}catch(Exception $e){
			return false;
		}
                
	}
	
	/**
	 * Load data of an user into the $auth_data static var
	 *
	 * @param string $username	User name
	 * @return boolean	True on success, false on failure
	 */
	public function loadUser($username){
		$users = DB::select('
			SELECT u.*, s.firstname, s.lastname, s.student_number, s.promo
			FROM users u
			LEFT JOIN students s ON s.username = u.username
			WHERE u.username = ?
		', array($username));
            if (isset($users[0])) {
                Sign_Model::$auth_data = $users[0];
            } else {
                throw new Exception('BAD_USER', 400);
            }

        //permet de checker l'autenticit� de l'admin
		if(isset(Sign_Model::$auth_data['admin']) && Sign_Model::$auth_data['admin']==1){
			if(Cache::read('auth_admin')){
				Cache::delete('auth_admin');
			}
			Cache::write('auth_admin',1,3600);
		}
		// If the user is a student
		if(isset(Sign_Model::$auth_data['student_number'])){
			// Avatar
			Sign_Model::$auth_data['avatar_url'] = Student_Model::getAvatarURL(Sign_Model::$auth_data['student_number'], true);
			Sign_Model::$auth_data['avatar_big_url'] = Student_Model::getAvatarURL(Sign_Model::$auth_data['student_number'], false);
		}
  }
}
