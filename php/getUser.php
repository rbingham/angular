<?php
	require_once("init.php");
	$data = json_decode(file_get_contents("php://input"));
	
	$user = new Registered($data->userName);
	$result = $user->login($data->userName,$data->password,"");

	if($result){
		setcookie("testCookie", "testing",time()+3600, '/','forum.dev');
		$return = new stdClass;
		$return->success = true;
		$return->data = $user->jsonSerialize();
		$return->data['cookie'] = $_COOKIE;
		echo json_encode($return);
	}else{
		$return = new stdClass;
		$return->success = false;
		$return->data = "";
		echo json_encode($return);
	}
?>