<?php

	/**
	* Tag class allows a thread to get and set the name of a tag
	*/
	class Tag {
		// ***** VARIABLES *****
		private $name = "Test name";
		// ***** VARIABLES *****

		// ***** CONSTRUCTOR *****
		// function __construct(argument)
		// {
		// 	# code...
		// }


		// ***** Getters & Setters *****

		public function getName(){
			return $this->$name;
		}

		public function setName($newName){
			$this->$name = $newName;
		}
	}

?>
