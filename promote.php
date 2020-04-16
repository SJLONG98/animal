<!DOCTYPE html>
<html>
  <head>
    <title>Angus' Animal Sanctuary</title>
  </head>
  
  <body>
	
	<?php
		include('header.php');
	  
		echo "<br><br>";
		
		session_start();
		
		if (isset($_SESSION["username"])) {
			if ($_SESSION["staff"] == "true") {
				require('menu.php');
				echo "<br>";
				include('mainstyle.php');
			} else {
				header('Location: home.php');
				exit();
			}
		} else {
			header('Location: login.php');
			exit();
		}
	
	
		echo "<div class=main>";
		
		function userSearch() {
			echo "
				<u><h4>Search for a User:</h4></u>
			
				<div width='400px'>
					<form name='input' action='promote.php' method='POST'>
						<label type='float:left'>User Name:</label>
						<input type='text' name='name' style='float:right;clear:right;width:200px'
							value='";if(isset($_POST['name'])) {echo htmlspecialchars($_POST['name']);}echo"'/>
						<br><br>
						<input type='hidden' name='search' value='true'/>
						<input type='submit' value='Search'/>
					</form>
				</div>
			";
		}
		$flag = 1;
		
		if ((!isset($_POST['search'])) && (!isset($_POST['userID']))) {
			userSearch();
		} elseif ((isset($_POST['search'])) && (!isset($_POST['userID']))) {
			if (empty($_POST["name"])) {
				userSearch();
				echo "<br><font color='red'>You haven't entered a value in the user name field.</font><br>";
				$flag = 0;
			} else {
				require_once('dbconnect.php');
				$userName  = $db->quote(htmlspecialchars($_POST["name"]));
				$result = $db->query("SELECT staff FROM user WHERE userID = $userName");
				$row = $result->fetch();
				if ($result->rowCount() == 0) {
					userSearch();
					echo "<br><font color='red'>Error - The username entered does not exist.</font><br>";
					$flag = 0;
				} elseif ($row['staff'] == 1) {
					userSearch();
					echo "<br><font color='red'>Error - The user is already a member of staff.</font><br>";
					$flag = 0;
				} else {
					echo "
						<div width='400px'>
							<form name='input' action='promote.php' method='POST'>
								<label type='float:left'>Are you sure you wish to promote $userName?</label><br><br>
								<input type='hidden' name='userID' value=$userName/>
								<input type='submit' name='submit' value='Yes'/>
								<input type='submit' name='submit' value='No'/>
							</form>
						</div>
					";
				}
			}
		} elseif (isset($_POST['userID'])) {
			if ($_POST['submit'] == 'Yes') {
				require_once('dbconnect.php');
				$userID = $db->quote($_POST['userID']);
				$db->exec("UPDATE user SET staff = 1 WHERE userID = $userID");
				echo "<SCRIPT>alert('You have successfully promoted the user to staff.');window.location.href='staffhome.php'</SCRIPT>";
			} elseif ($_POST['submit'] == 'No') {
				userSearch();
			}
		}
		
	?>
	
	</div>
	
  </body>
  
</html>