<?php

require_once('Tag.class.php');

class ForumThread implements JsonSerializable {

    // ***** VARIABLES *****
    private $title = "Test Title";
    private $status = "T";
    private $authorID = "0";
    private $date = "01/01/14";
    private $content = "test content";
    private $mysql;
    private $threadID;
    private $views;
    public $posts = array();
    public $error;

    // ***** CONSTRUCTOR *****
    public function __construct($threadID, $createNEW = false, $data = array()) {
        
       $this->mysql = new mysqli(dbHost, dbUser, dbPass, dbName);
        
        //create new Thread
        if($createNEW && isset($data) && is_array($data)){
            $this->title= $this->mysql->real_escape_string($data['title']);
            $this->status = $this->mysql->real_escape_string($data['status']);
            $this->authorID = $this->mysql->real_escape_string($data['authorID']);
            $this->date = $this->mysql->real_escape_string($data['date']);
            $this->content = $this->mysql->real_escape_string($data['content']);
            $this->threadID = $threadID;
                                
            $result = $this->mysql->query( "INSERT INTO `thread`"
                    ."(`authorID`, `title`, `content`, `status`)"
                    ."VALUES ('{$this->authorID}','{$this->title}','{$this->content}',"
                        . "'{$this->status}')");   
                        
            $result = $this->mysql->query("SELECT `threadID` FROM `thread` WHERE `title` = '{$this->title}' AND `authorID`= '{$this->authorID}'");
            $row = mysqli_fetch_array($result);
            $this->threadID = $row['threadID'];
        } else { //get Thread from database with threadID
            $result = $this->mysql->query( "SELECT * FROM `thread`"
                  . " WHERE `threadID` = '{$threadID}'" );
            if($result->num_rows == 1){
                $this->error = false;
                $row = mysqli_fetch_array($result);
                $this->title = $row['title'];
                $this->status = $row['status'];
                $this->authorID = $row['authorID'];
                $this->date = $row['date'];
                $this->content = $row['content'];
                $this->threadID = $threadID;
                $this->views = $row['views'];
                
                $result = $this->mysql->query( "SELECT `postID` FROM `post` "
                . " WHERE `threadID` = '$this->threadID'" );
                
                if($result) {
                    while($postID = mysqli_fetch_array($result)){
                            $postItem = new Post($postID['postID'], false);
                            $this->posts[$postID['postID']] = $postItem;
    	            }
                }
            } else {
                $this->error = true;
            }
        }
    }

    public function jsonSerialize() {
        $return = get_object_vars($this);
        $return['mysql'] = "";
        return $return;
    }

	public function getPosts(){
		return $this->posts;
	}

	public function addPosts($post){
        $post = new Post(-1,true,$post);
        $this->posts[$post->getPostID()] = $post;
	}

    public function increaseViews(){
        $this->mysql->query("UPDATE thread SET views={$this->views} + 1 where threadID = {$this->threadID}");
    }

	
        // ***** GETTERS & SETTERS *****

    public function getTitle(){
          return $this->title; 
    }


    public function getViews(){
        return $this->views;
    }

    public function setTitle($newTitle){
            $this->title = $newTitle;
            $result = $this->mysql->query("UPDATE `thread`"
                    . " SET `title` = '$newTitle' WHERE `threadID` = '{$this->threadID}'");      
    }

    public function getStatus(){
            return $this->status;
    }

    public function setStatus($newStatus){
            $this->status = $newStatus;
            $result = $this->mysql->query("UPDATE `thread`"
                    . " SET `status`='$newStatus' WHERE `threadID` = '{$this->threadID}'");
    }

    public function getAuthorID(){
            return $this->authorID;
    }

    public function setAuthorID($newAuthorID){
            $this->authorID = $newAuthorID;
            $result = $this->mysql->query("UPDATE `thread` "
                    . "SET `authorID`='$newAuthorID' WHERE `threadID` = '{$this->threadID}'");
    }

    public function getDate(){
            return $this->date;
    }

    public function setDate($newDate){
            $this->date = $newDate;
            $result = $this->mysql->query("UPDATE `thread` "
                    . "SET `date`='$newDate' WHERE `threadID` = '{$this->threadID}'");
    }

    public function getContent(){
        return $this->content;
    }

    public function setContent($newContent){
        $this->content = $newContent;
        $result = $this->mysql->query("UPDATE `thread` "
                    . "SET `content`='$newContent' WHERE `threadID` = '{$this->threadID}'");
    }
}
?>


