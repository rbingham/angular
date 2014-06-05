<?php

	class CusSessionHandler implements SessionHandlerInterface{
		public function close (){
			return false;
		}

		public function destroy ( $session_id ){
			return false;
		}

		public function gc ( $maxlifetime ){
			return false;
		}

		public function open ( $save_path, $session_id ){
			return false;
		}

		public function read ( $session_id ){
			return "Hello";
		}

		public function write ( $session_id, $session_data ){
			return false;
		}

	}
?>