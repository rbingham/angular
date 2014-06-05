<?php

	require_once('Registered.class.php');
	class Admin extends Registered{
		
		public function __construct($userName){
			parent::__construct($userName);
		}
		
		public function createUser($userData = array()){
			$newUser = new User();
			$result = $newUser->register($userData);
			return $result;
		}

		public function editUser($userName, $userData = array()){
			if(!empty($userData)){
				$editUser = new Registered($userName);
				$userId = $editUser->getUserId();
				foreach($userData as $key => $value){
					switch($key){
						case 'userName':
							$queryResult = $editUser->setUserName($value, $userId, true);
							break;
						case 'firstName':
							$queryResult = $editUser->setFirstName($value, $userId, true);
							break;
						case 'lastName':
							$queryResult = $editUser->setLastName($value, $userId, true);
							break;
						case 'email':
							$queryResult = $editUser->setEmail($value, $userId, true);
							break;
						case 'password':
							$queryResult = $editUser->setPassword($value, $userId, true);
							break;
						case 'isAdmin':
							$queryResult = $editUser->setIsAdmin($value, $this->userName);
							break;
					}
					if($queryResult)
						$result[$key] = "Updated $key successfully\n";
					else
						$result[$key] =  "Failed to update $key\n";
				}
				return $result;
			}else{
				$result = false;
				return $result;
			}
		}

		public function deleteUser($userName, $auth = true){
			
			$userName = $this->mysql->real_escape_string($userName);
			$query1 = "SELECT `userID` FROM `user` WHERE userName='$userName';";

			$result = $this->mysql->query($query1);
			
			if($result->num_rows === 1){
				$data = $result->fetch_assoc();
				$userId = $data['userID'];
				$result = parent::deleteUser($userId, $auth);
			}
			else
				$result = false;

			return $result;
		}

		public function resetPassword($userName, $password = ""){
			$registered = new Registered($userName);
			if(isset($password) && $password != null){
				$result = $registered->setPassword($password, $registered->getUserId(), true);
			}else{
				$password = $this->createRandomPassword();
				$result = $registered->setPassword($password, $registered->getUserId(), true);
			}
			return $result;
		}

		public function createRandomPassword($length = 8){
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
			$password = "";
			for ($i = 0; $i < $length; $i++) {
				$x = mt_rand(0, strlen($chars) - 1);
				$password .= $chars[$x];
			}
			return $password;
		}

		public function createThreadCategory(){

		}
	}
