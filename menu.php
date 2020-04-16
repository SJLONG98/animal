<?php
    if (isset($_SESSION["username"])) {
		if ($_SESSION["staff"] == "true") {
			require('menu2.php');
			echo "
			 <style>
				 .menu {
					 width:350px;
					 margin-left:32px;
					 clear:both;
					 border-right:1px solid #000000;
				 }
			 </style>
			  
			  <div class='menu'>
			  <br>
			  <u>Staff Actions</u>
			  <ul style='list-style-type:disc'>
				<li><a href='staffhome.php'>Staff Home</a></li>
				<br>
				<li><a href='alladoptrequests.php'>View All Adoption Requests</a></li>
				<li><a href='adoptrequests.php'>View Pending Adoption Requests</a></li>
				<br>
				<li><a href='viewallanimals.php'>View All Animals</a></li>
				<!--<li><a href='viewanimals.php'>View Available Animals</a></li>    Commented out as staff can use the link in user actions      -->
				<!--<li><a href='searchanimal.php'>Search for an Animal</a></li>    Commented out as staff can use the link in user actions      -->
				<li><a href='addanimal.php'>Add An Animal</a></li>
				<li><a href='removeanimal.php'>Remove An Animal</a></li>
				<br>
				<li><a href='staffreset.php'>Reset User Password</a></li>
				<li><a href='promote.php'>Promote A User To Staff</a></li>
			  </ul>
			  </div>
			";
		} else {
			require('menu2.php');
		}
	} else {
		header('Location: login.php');
		exit();
	}
?>