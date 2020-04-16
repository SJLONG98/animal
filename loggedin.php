<?php
	
	session_start();
	
	if (!isset($_SESSION["username"])) {
		header('Location: login.php');
		exit();
	} else {
		if ($_SESSION["staff"] == "true") {
			header('Location: staffhome.php');
			exit();
		} else {
			header('Location: home.php');
			exit();
		}
	}
	
?>