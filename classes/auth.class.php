<?php
	namespace AF;
	use \AF\Session;
	
	class AuthFactory {
		private static $instance = NULL;
		
		public static function getInstance() {
			if(self::$instance === NULL) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		public function isLoggedIn() {
			$user_id = Session::get('user_id');
			
			return (!empty($user_id) && $user_id > 0);
		}
		
		public function getID() {
			return Session::get('user_id');
		}
		
		public function getUsername() {
			return Session::get('user_name');
		}
		
		public function logout() {
			Session::remove('user_id');
			Session::remove('user_name');
		}
		
		public function login($username, $password) {
			$result = Database::single('SELECT * FROM `af_users` WHERE `username`=:username AND `password`=MD5(:password) LIMIT 1', array(
				'username'	=> $username,
				'password'	=> $password
			));
			
			if($result == false) {
				throw new \Exception('Unknown User');
				return false;
			}
			
			if($result->id > 0) {
				Session::set('user_name',	$result->username);
				Session::set('user_id',		$result->id);
			} else {
				throw new \Exception('Unknown User');
				return false;				
			}
			
			return true;
		}
	}
	
	class Auth {
		public static function isLoggedIn() {
			return AuthFactory::getInstance()->isLoggedIn();
		}
		
		public static function login($username, $password) {
			return AuthFactory::getInstance()->login($username, $password);
		}
		
		public static function logout() {
			return AuthFactory::getInstance()->logout();
		}
		
		public static function getID() {
			return AuthFactory::getInstance()->getID();
		}
		
		public static function getUsername() {
			return AuthFactory::getInstance()->getUsername();
		}
	}
?>