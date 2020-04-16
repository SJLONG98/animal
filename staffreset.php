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
					<form name='input' action='staffreset.php' method='POST'>
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
		
		function resetForm($userName) {
			echo "
				<u><h4>Reset a Users Password:</h4></u>
				Note: The password must be at least 8 characters<br> in length including a number and a capital letter.<br><br>
				<div width='400px'>
					<form name='input' action='staffreset.php' method='POST'>
						<label type='float:left'>Password:</label>
						<input type='password' name='password' style='float:right;clear:right;width:200px'/>
						<br><br>
						<label type='float:left'>Confirm Password:</label>
						<input type='password' name='password2' style='float:right;clear:right;width:200px'/>
						<br><br>
						<input type='hidden' name='userID' value=$userName/>
						<input type='submit' value='Reset'/>
					</form>
				</div>
			";
		}
		
		if ((!isset($_POST['search'])) && (!isset($_POST['userID']))) {
			userSearch();
		} elseif ((isset($_POST['search'])) && (!isset($_POST['userID']))) {
			if (empty($_POST["name"])) {
				userSearch();
				echo "<br><font color='red'>You haven't entered a value in the user name field.</font><br>";
			} else {
				require_once('dbconnect.php');
				$userName  = $db->quote(htmlspecialchars($_POST["name"]));
				$result = $db->query("SELECT staff FROM user WHERE userID = $userName");
				$row = $result->fetch();
				if ($result->rowCount() == 0) {
					userSearch();
					echo "<br><font color='red'>Error - The username entered does not exist.</font><br>";
				} elseif ($row['staff'] == 1) {
					userSearch();
					echo "<br><font color='red'>Error - You cannot reset a member of staff's password.</font><br>";
				} else {
					resetForm($userName);
				}
			}
		} elseif (isset($_POST['userID'])) {
			require_once('dbconnect.php');
			$userName  = $db->quote(htmlspecialchars($_POST["userID"]));
			if ((empty($_POST["password"])) && (empty($_POST["password2"]))) {
				resetForm($userName);
				echo "<br><font color='red'>You haven't entered a value in any password fields.</font><br>";
			} elseif (empty($_POST["password"])) {
				resetForm($userName);
				echo "<br><font color='red'>You haven't entered a value in the password field.</font><br>";
			} elseif (empty($_POST["password2"])) {
				resetForm($userName);
				echo "<br><font color='red'>You haven't entered a value in the confirm password field.</font><br>";
			} elseif ($_POST["password"] !== $_POST["password2"]) {
				resetForm($userName);
				echo "<br><font color='red'>The two password fields do not match.</font><br>";
			} elseif  ((!preg_match("/[A-Z]/",$_POST["password"])) || (!preg_match("/[a-z]/",$_POST["password"])) || (!preg_match("/[0-9]/",$_POST["password"])) || (strlen($_POST["password"]) < 8)) {
				resetForm($userName);
				echo "<br><font color='red'>The password must meet the above criteria</font><br>";
			} else {
				$password = $db->quote(md5($_POST['password']));
				try {
					$db->exec("UPDATE user SET password = $password WHERE userID = $userName");
					echo "<SCRIPT>alert('You have successfully reset the users password.');window.location.href='staffhome.php'</SCRIPT>";
				} catch (PDOException $e) {
					echo "Database error resetting the users password<br>" . $e->getMessage();
					echo "<br>Please contact an administrator";
				}
			}
		}
		
	?>
	
	</div>
	
  </body>
  
</html>