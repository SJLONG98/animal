<?php
	$db = new PDO("mysql:dbname=HelpingHeroes_DB;host=localhost","Admin","Admin");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>