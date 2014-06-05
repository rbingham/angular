<?php

require_once('User.class.php');

class Registered extends User implements JsonSerializable {
	private $userId;
	private $password;
	private $email;
    private $loggedIn = false;
	private $error;

    public function jsonSerialize() {
        $return = get_object_vars($this);
        $return['password'] = "";
        $return['mysql'] = "";
        return $return;
    }

	//constructor. Requires username.
	public function __construct($userName){
		parent::__construct();
		$result = $this->mysql->query("SELECT * FROM user WHERE username='{$userName}';");

		if($result->num_rows === 1){
			$data = $result->fetch_assoc();
			$this->userId = $data['userID'];
			$this->userName = $data['userName'];
			$this->password = $data['password'];
			$this->firstName = $data['firstName'];
			$this->lastName = $data['lastName'];
			$this->email = $data['email'];
			$this->isAdmin = $data['isAdmin'];
		}else{
			$this->error = true;
		}
	}
	
	public function getError(){
		return $this->error;
	}

    public function loggedIn(){
        return $this->loggedIn;
    }

	//returns the password of the user
	public function getPassword(){
		return $this->password;
	}

	//returns userId
	public function getUserId(){
		return $this->userId;
	}

	//returns user email address
	public function getEmail(){
		return $this->email;
	}

	public function setUserName($userName, $userId, $auth = false){
                $userId = $this->mysql->real_escape_string($userId);
                $userName = $this->mysql->real_escape_string($userName);
                $query1 = "SELECT `isAdmin` FROM `user` WHERE userID = '$userId';";
                $query2 = "UPDATE `user` SET `userName`= '$userName' WHERE userName = '{$this->userName}' AND userID = '{$this->userId}';";

                if($auth === false && $userId == $this->userId)
                        $auth = true;
                elseif($auth === false){
                        $result = $this->mysql->query($query1);
                        $data = $result->fetch_assoc();
                        if($data['isAdmin']){
                                $auth = true;
                        }
                }

                if($auth){
                        $result = $this->mysql->query($query2);
                        $this->userName = $userName;
                        return true;
                }else{
                        return false;
                }
        }


	//sets the password for the user. Requires authorized userId to change the password.
	public function setPassword($password, $userId, $auth = false){
		$userId = $this->mysql->real_escape_string($userId);
		$password = $this->mysql->real_escape_string($password);
		$query1 = "SELECT `isAdmin` FROM `user` WHERE userID = '$userId';";
		$query2 = "UPDATE `user` SET `password`= '$password' WHERE userName = '{$this->userName}' AND userID = '{$this->userId}';";

		if($auth === false && $userId == $this->userId)
			$auth = true;
		elseif($auth === false){
			$result = $this->mysql->query($query1);
			$data = $result->fetch_assoc();
			if($data['isAdmin']){
				$auth = true;
			}
		}

		if($auth){
			$result = $this->mysql->query($query2);
			$this->password = $password;
			return true;
		}else{
			return false;
		}
	}


	//sets email address for user. Requires authorized userId to change email address
	public function setEmail($email, $userId, $auth = false){
                $userId = $this->mysql->real_escape_string($userId);
                $email = $this->mysql->real_escape_string($email);
                $query1 = "SELECT `isAdmin` FROM `user` WHERE userID = '$userId';";
                $query2 = "UPDATE `user` SET `email`= '$email' WHERE userName = '{$this->userName}' AND userID = '{$this->userId}';";

                if($auth === false && $userId == $this->userId){
                    $auth = true;
                }elseif($auth === false){
                    $result = $this->mysql->query($query1);
                    $data = $result->fetch_assoc();
                    if($data['isAdmin']){
                            $auth = true;
                    }
                }

                if($auth){
                    $result = $this->mysql->query($query2);
                    $this->email = $email;
                    return true;
                }else{
                    return false;
                }
	}

	//set isAdmin. Requires username of an user that is already an admin.
        public function setIsAdmin($isAdmin, $adminUser){
		$auth = false;
                $isAdmin = $this->mysql->real_escape_string($isAdmin);
                $adminUser = $this->mysql->real_escape_string($adminUser);
                $query1 = "SELECT `isAdmin` FROM `user` WHERE userID = '$adminUser';";
                $query2 = "UPDATE `user` SET `isAdmin`= '$isAdmin' WHERE userName = '{$this->userName}' AND userID = '{$this->userId}';";

                $result = $this->mysql->query($query1);
                $data = $result->fetch_assoc();
                if($data['isAdmin']){
                	$auth = true;
                }

                if($auth){
                        $result = $this->mysql->query($query2);
                        $this->isAdmin = $isAdmin;
                        return true;
                }else{
                        return false;
                }
 
        }

	//sets user lastName
        public function setLastName($lastName, $userId, $auth = false){
                $userId = $this->mysql->real_escape_string($userId);
                $lastName = $this->mysql->real_escape_string($lastName);
                $query1 = "SELECT `isAdmin` FROM `user` WHERE userID = '$userId';";
                $query2 = "UPDATE `user` SET `lastName`= '$lastName' WHERE userName = '{$this->userName}' AND userID = '{$this->userId}';";

                if($auth === false && $userId == $this->userId)
                        $auth = true;
                elseif($auth === false){
                        $result = $this->mysql->query($query1);
                        $data = $result->fetch_assoc();
                        if($data['isAdmin']){
                                $auth = true;
                        }
                }

                if($auth){
                        $result = $this->mysql->query($query2);
                        $this->lastName = $lastName;
                        return true;
                }else{
                        return false;
                }
        }
        //sets user firstName
        public function setFirstName($firstName, $userId, $auth = false){
                $userId = $this->mysql->real_escape_string($userId);
                $firstName = $this->mysql->real_escape_string($firstName);
                $query1 = "SELECT `isAdmin` FROM `user` WHERE userID = '$userId';";
                $query2 = "UPDATE `user` SET `firstName`= '$firstName' WHERE userName = '{$this->userName}' AND userID = '{$this->userId}';";

                if($auth === false && $userId == $this->userId)
                        $auth = true;
                elseif($auth === false){
                        $result = $this->mysql->query($query1);
                        $data = $result->fetch_assoc();
                        if($data['isAdmin']){
                                $auth = true;
                        }
                }

                if($auth){
                        $result = $this->mysql->query($query2);
                        $this->firstName = $firstName;
                        return true;
                }else{
                        return false;
                }
        }



	//logs in the user. returns true on success, false on failure
	public function login($userName, $password, $remember){
        if(!$this->error && strcmp($password,$this->password) === 0 && strcmp($userName, $this->userName) === 0){
            $this->loggedIn = true;
            $cookie = $this->userName . ":" . bin2hex(openssl_random_pseudo_bytes(16));
            $token = bin2hex(openssl_random_pseudo_bytes(16));
            $ip = $_SERVER['REMOTE_ADDR'];
			return true;
        }
		else
			return false;
	}

	//Deletes User
	public function deleteUser($userId, $auth = false){
		$userId = $this->mysql->real_escape_string($userId);
                $query1 = "SELECT `isAdmin` FROM `user` WHERE userID = '$userId';";
                $query2 = "DELETE FROM `user` WHERE `userID` = '{$this->userId}'";

                if($auth === false && $userId == $this->userId)
                        $auth = true;
                elseif($auth === false){
                        $result = $this->mysql->query($query1);
                        $data = $result->fetch_assoc();
                        if($data['isAdmin']){
                                $auth = true;
                        }
                }

                if($auth){
                        $result = $this->mysql->query($query2);
                        return true;
                }else{
                        return false;
                }
	}

	public function updateUser($userData){
		if(!empty($userData)){
			$userId = $this->getUserId();
                        foreach($userData as $key => $value){
                        	switch($key){
                                	case 'userName':
						$queryResult = $this->setUserName($value, $userId);
                                        	break;
                                        case 'firstName':
                                      		$queryResult = $this->setFirstName($value, $userId);
                                                break;
                                        case 'lastName':
                                                $queryResult = $this->setLastName($value, $userId);
                                                break;
                                        case 'email':
                                                $queryResult = $this->setEmail($value, $userId);
                                                break;
                                        case 'password':
                                                $queryResult = $this->setPassword($value, $userId);
                                                break;
                                       }
                                        if($queryResult){
						$this->$key = $value;
                                               $result[$key] =  array('result' => true, 'msg' => "Updated $key successfully");
					}else{
                                               $result[$key] = array('result' => false, 'msg' =>"Failed to update $key");
					}
                               }
			return $result;
		}else{
			return false;
		}

	}

	//creates a comment on a thread. Requires threadId. Returns true on success, false on failure.
	public function createComment($threadId, $comment){
		$threadId = $this->mysql->real_escape_string($threadId);
		$comment = $this->mysql->real_escape_string($comment);

		$data = array(
			'threadID' => $threadId,
			'content' => $comment,
			'authorID' => $this->userId);

		$newComment = new Comment(0, true, $data);
	
		if($newComment->getCommentID() > 0)
			return true;
		else
			return false;
	}
	
	//ups or lowers the rate of the comment
	public function rateComments($commentId, $rate){

	}
	
	//creates a thread under the user. Receives the threadName, subject of thread, tags associated with thread
	//and the body of the thread. Returns true on successful creation of thread false on failure.
	public function createThread($threadName, $subject, $tags, $body){

	}
	
	//function to edit thread
	public function editThread(){
		
	}

	//function to delete thread. Returns true on success, false on failure
	public function deleteThread($threadId){

	}

	//edits comments. Receives comment id and edited comment string. Returns true on success, false on failure
	public function editComment($commentId, $comment){

	}

	//delets a comment. Receives commentId. Returns true on success, false on failure
	public function deleteComment($commentId){
		$comment = $this->mysql->real_escape_string($commentId);
		$comment = new Comment($commentId);
		return $comment->deleteComment();
	}
}
