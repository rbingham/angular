<?php
	/**
	* Comment class
	*/
	class Comment implements JsonSerializable{	

		private $content;
		private $authorID;
		private $commentID;
		private $postID;
		private $threadID;
		private $date;
		private $mysql;
		
		
	 	// ***** CONSTRUCTOR *****
	 	public function __construct($commentID, $createNew = false, $data = array()){
			$this->mysql = new mysqli(dbHost,dbUser,dbPass,dbName);

			if($createNew && isset($data) && is_array($data)){
				$threadID = $this->mysql->real_escape_string($data['threadID']);
				$authorID = $this->mysql->real_escape_string($data['authorID']);
				$postID = $this->mysql->real_escape_string($data['postID']);
				$content = $this->mysql->real_escape_string($data['content']);
				$date = date('Y m d');

				$query = "INSERT INTO `comment` (`threadID`, `postID`, `authorID`, `content`, `date`) VALUES ('{$threadID}', '{$postID}', '{$authorID}', '{$content}', CURDATE());";
				$result = $this->mysql->query($query);

				$this->content = $content;
				$this->authorID = $authorID;
				$this->postID = $postID;
				$this->commentID = $this->mysql->insert_id;
				$this->threadID = $threadID;
				$this->date = $date;
			}else{
				$query = "SELECT * FROM `comment` WHERE `commentID`='$commentID'";
				
				$result = $this->mysql->query($query);

				$data = $result->fetch_assoc();
				$this->content = $data['content'];
				$this->authorID = $data['authorID'];
				$this->postID = $data['postID'];
				$this->threadID = $data['threadID'];
				$this->date = $data['date'];
				$this->commentID = $data['commentID'];
			}
	 	}

		public function jsonSerialize() {
	        $return = get_object_vars($this);
        	$return['mysql'] = "";
	        return $return;
    	}

		// ***** GETTERS *****
		public function getContent(){
			return $this->content;
		}

		public function getAuthId(){
			return $this->authID;
		}

		public function getDate(){
			return $this->date;
		}

		public function getCommentID(){
			return $this->commentID;
		}

		public function getPostID(){
			return $this->postID;
		}

		// ***** SETTERS *****
		public function setContent($content){
			$content = $this->mysql->real_escape_string($content);
			$query = "UPDATE `comment` SET `content`='$content' WHERE commentID='{$this->commentID}'";
			$result = $this->mysql->query($query);
                        if($result === true){
                                $this->content = $content;
                        }
			return $result;
		}

		public function setAuthId($authorID){
			$authorID = $this->mysql->real_escape_string($authorID);
                        $query = "UPDATE `comment` SET `authorID`='$authorID' WHERE commentID='{$this->commentID}'";
                        $result = $this->mysql->query($query);
                        if($result === true){
                                $this->authorID = $authorID;
                        }
                        return $result;
		}

		public function setDate($date){
			$date = $this->mysql->real_escape_string($date);
                        $query = "UPDATE `comment` SET `date`='$date' WHERE commentID='{$this->commentID}'";
                        $result = $this->mysql->query($query);
			if($result === true){
				$this->date = $date;
			}
                        return $result;
		}

		public function deleteComment(){
			$result = $this->mysql->query("DELETE FROM `comment` WHERE `commentID` = '{$this->commentID}'");
			return $result;
		}
	}


?>
