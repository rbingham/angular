<?php
	require_once("init.php");

    $data = json_decode(file_get_contents("php://input"));
	$thread = new ForumThread($data->threadID);

	if($thread->error === false){
		$return = new stdClass;
		$return->success = true;
		$return->data = $thread->jsonSerialize();
		echo json_encode($return);
	} else {
		$return = new stdClass;
		$return->success = false;
		$return->data = "";
		echo json_encode($return);	
	}

?>
