<?php
	require '../backend/bd/ctconex.php';
	session_destroy();
	$url = "../index.php";
	header("Location: $url");
?>
