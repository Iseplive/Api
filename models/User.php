<?php

class User_Model extends Model {
	
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
                User_Model::$auth_data = $users[0];
            } else {
                throw new Exception('BAD_USER', 400);
            }

        //permet de checker l'autenticit� de l'admin
		if(isset(User_Model::$auth_data['admin']) && User_Model::$auth_data['admin']==1){
			if(Cache::read('auth_admin')){
				Cache::delete('auth_admin');
			}
			Cache::write('auth_admin',1,3600);
		}
		// If the user is a student
		if(isset(User_Model::$auth_data['student_number'])){
			// Avatar
			User_Model::$auth_data['avatar_url'] = Student_Model::getAvatarURL(User_Model::$auth_data['student_number'], true);
			User_Model::$auth_data['avatar_big_url'] = Student_Model::getAvatarURL(User_Model::$auth_data['student_number'], false);
		}
  }
  
  /**
	 * Save the data of a user
	 *
	 * @param int $id		user's id
	 * @param array $data	user's data
	 */
	public function save($id, $data){
		$user_data = array();
		
		// Email
		if(isset($data['mail'])){
			if($data['mail'] != '' && !Validation::isEmail($data['mail']))
					throw new FormException('mail');
			$user_data['mail'] = $data['mail'];
		}
		
		// MSN
		if(isset($data['msn'])){
			if($data['msn'] != '' && !Validation::isEmail($data['msn']))
					throw new FormException('msn');
			$user_data['msn'] = $data['msn'];
		}
		
		// Jabber
		if(isset($data['jabber'])){
			if($data['jabber'] != '' && !Validation::isEmail($data['jabber']))
					throw new FormException('jabber');
			$user_data['jabber'] = $data['jabber'];
		}
		
		// Address
		if(isset($data['address']))
			$user_data['address'] = $data['address'];
		
		// Zipcode
		if(isset($data['zipcode'])){
			if($data['zipcode'] != '' && !ctype_digit(trim($data['zipcode'])))
				throw new FormException('zipcode');
			$user_data['zipcode'] = $data['zipcode'] == '' ? null : (int) trim($data['zipcode']);
		}
		
		// City
		if(isset($data['city']))
			$user_data['city'] = $data['city'];
		
		// Cellphone
		if(isset($data['cellphone'])){
			if($data['cellphone'] != '' && !preg_match('#^0[67]([ \.-]?)[0-9]{2}(?:\1[0-9]{2}){3}$#', $data['cellphone']))
				throw new FormException('cellphone');
			$user_data['cellphone'] = $data['cellphone'];
		}
		
		// Phone
		if(isset($data['phone'])){
			if($data['phone'] != '' && !preg_match('#^0[1-59]([ \.-]?)[0-9]{2}(?:\1[0-9]{2}){3}$#', $data['phone']))
				throw new FormException('phone');
			$user_data['phone'] = $data['phone'];
		}
		
		// Birthday
		if(isset($data['birthday'])){
			if(!($birthday = strptime($data['birthday'], __('USER_EDIT_FORM_BIRTHDAY_FORMAT_PARSE'))))
					throw new FormException('birthday');
			$user_data['birthday'] = ($birthday['tm_year']+1900).'-'.($birthday['tm_mon']+1).'-'.$birthday['tm_mday'];
		}
		
		// Update the DB
		$this->createQuery()
			->set($user_data)
			->update($id);
		
	}
}
