<?php

class User{
	protected $userName = "Guest";
	protected $firstName = "Guest";
	protected $isAdmin = false;
	protected $lastName = "";
	protected $mysql;

	public function __construct(){
		$this->mysql = new mysqli(dbHost,dbUser,dbPass,dbName);
	}

	//receives user data as an array and creates an account for them. Array variable names should be the same as database names.
	public function register($userData = array()){
		if(!empty($userData))
			$result = $this->mysql->query("INSERT INTO `user`(`firstName`, `lastName`, `userName`, `password`, `email`) VALUES ('{$userData['firstName']}','{$userData['lastName']}', '{$userData['userName']}','{$userData['password']}','{$userData['email']}');");
		else
			$result = false;
		if($result)
			return true;
		else
			return false;
	}
	
	public function getUserName(){
		return $this->userName;
	}

	//returns user firstName
	public function getFirstName(){
		return $this->firstName;
	}

	//returns user lastName
	public function getLastName(){
		return $this->lastName;
	}

	//returns isAdmin
	public function isAdmin(){
		return $this->isAdmin;
	}

	//searches for a thread that is on topic
	public function searchTread($topic){

	}

	//pulls data for threadId and returns it
	public function viewTread($threadId){

	}
}
