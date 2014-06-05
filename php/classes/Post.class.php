<?php
	require_once("Thread.class.php");

	/**
	* Post Class
	*/
	class Post implements JsonSerializable {

		// ***** VARIABLES *****
		private $postID;
		private $content;
		private $authorID;
		private $threadID;
		private $date;
		public  $error;
		private $comments = array();

		//***** CONSTRUCTORS ******
		public function __construct($postID, $createNew = false, $data = array()){
			
			$this->mysql = new mysqli(dbHost, dbUser, dbPass, dbName);

			if($createNew && isset($data) && is_array($data)) {
                    $this->content = $this->mysql->real_escape_string($data['content']);
                    $this->authorID = $this->mysql->real_escape_string($data['authorID']);
                    $this->threadID = $this->mysql->real_escape_string($data['threadID']);
                                                            
                    $result = $this->mysql->query( "INSERT INTO `post` (`threadID`, `authorID`, `content`, `date`) VALUES ('{$this->threadID}','{$this->authorID}','{$this->content}', CURDATE())");
                    $this->error = $this->mysql->error;
                    $result = $this->mysql->query("SELECT `postID`, `date` FROM `post` WHERE `content` = '{$this->content}' AND `authorID`= '{$this->authorID}'");
                    $row = mysqli_fetch_array($result);
                    $this->postID = $row['postID'];
                    $this->date = $row['date'];
            } else { //get post from database with postID
                $result = $this->mysql->query( "SELECT * FROM `post`"
                    . " WHERE `postID` = '{$postID}'" );
                    
                $row = mysqli_fetch_array($result);
                $this->authorID = $row['authorID'];
                $this->date = $row['date'];
                $this->content = $row['content'];
                $this->postID = $postID;
                
                $result = $this->mysql->query( "SELECT `commentID` FROM `comment` WHERE `postID` = {$this->postID}" );
				
				while($comment = mysqli_fetch_array($result)){
					$commentItem = new Comment($comment['commentID'], false);
					$this->comments[$comment['commentID']] = $commentItem;
				}		
			}
		}

		public function jsonSerialize() {
	        $return = get_object_vars($this);
        	$return['mysql'] = "";
	        return $return;
    	}


		// add comment
		public function addComment($commentContent){
			//array_push($comments,)
		}
		// delete comment
		// edit comment

		// add post

		// edit post

		// delete post




		// ***** GETTERS *****

		public function getPostID(){
			return $this->postID;
		}

		public function getContent(){
			return $this->content;
		}

		public function getAuthorID(){
			return $this->authorID;
		}


		public function getDate(){
			return $this->date;
		}

		public function setAuthorID($authorID){
			$authorID = $this->mysql->real_escape_string($authorID);
            $query = "UPDATE `post` SET `authorID`='$authorID' WHERE postID='{$this->postID}'";
            $result = $this->mysql->query($query);
            if($result === true){
                $this->authorID = $authorID;
            }
            return $result;
		}

		public function setDate($date){

		}

	}
?>
