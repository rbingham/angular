<?php
	require_once("init.php");

	$data = json_decode(file_get_contents("php://input"));
	$data = get_object_vars($data);

	$newPost = new Post(-1, true, $data);

	$return = new stdClass;
	$return->success = true;
	$return->data = $newPost;

	echo json_encode($return);
?>