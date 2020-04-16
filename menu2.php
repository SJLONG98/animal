<?php
    if (isset($_SESSION["username"])) {
		$currentDate = getdate();
		echo "
		 <style>
			 .userlog {
				 position:absolute;
				 right:0px;
				 width:300px;
				 margin-right:50px;
			 }
			 
			 .menu2 {
				 width:350px;
				 margin-left:32px;
				 clear:both;
				 border-right:1px solid #000000;
				 border-bottom:1px solid #000000;
			 }
		 </style>
		 
		 <div class='userlog'>
		 Currently logged in as: $_SESSION[username] <br> Current date: $currentDate[weekday], $currentDate[mday] $currentDate[month]
		 </div>
		 
		  <div class='menu2'>
		  <a href='logout.php'>Logout</a>
		  <br><br>
		  <u>User Actions</u>
		  <ul style='list-style-type:disc'>
			<li><a href='home.php'>User Home</a></li>
			<br>
			<li><a href='useradoptrequests.php'>View My Pending Adoption Requests</a></li>
			<li><a href='useralladoptrequests.php'>View All of My Adoption Requests</a></li>
			<br>
			<li><a href='viewanimals.php'>View My Animals</a></li>
			<li><a href='viewavailanimals.php'>View Available Animals</a></li>
			<li><a href='searchanimal.php'>Search for an Animal</a></li>
			<br>
			<li><a href='userreset.php'>Reset My Password</a></li>
		  </ul>
		  </div>
		";
	} else {
		header('Location: login.php');
		exit();
	}
?>