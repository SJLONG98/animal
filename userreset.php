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
			require('menu.php');
			echo "<br>";
			include('mainstyle.php');
		} else {
			header('Location: login.php');
			exit();
		}
	
		echo "<div class=main>";
		
		function resetForm() {
			echo "
				<u><h4>Reset My Password:</h4></u>
				Note: Your password must be at least 8 characters<br> in length including a number and a capital letter.<br><br>
				<div width='400px'>
					<form name='input' action='userreset.php' method='POST'>
						<label type='float:left'>Password:</label>
						<input type='password' name='password' style='float:right;clear:right;width:200px'/>
						<br><br>
						<label type='float:left'>Confirm Password:</label>
						<input type='password' name='password2' style='float:right;clear:right;width:200px'/>
						<br><br>
						<input type='hidden' name='submitted' value='true'/>
						<input type='submit' value='Reset'/>
					</form>
				</div>
			";
		}
		
		if (!isset($_POST['submitted'])) {
			resetForm();
		} else {
			if ((empty($_POST["password"])) && (empty($_POST["password2"]))) {
				resetForm();
				echo "<br><font color='red'>You haven't entered a value in any password fields.</font><br>";
			} elseif (empty($_POST["password"])) {
				resetForm();
				echo "<br><font color='red'>You haven't entered a value in the password field.</font><br>";
			} elseif (empty($_POST["password2"])) {
				resetForm();
				echo "<br><font color='red'>You haven't entered a value in the confirm password field.</font><br>";
			} elseif ($_POST["password"] !== $_POST["password2"]) {
				resetForm();
				echo "<br><font color='red'>The two password fields do not match.</font><br>";
			} elseif  ((!preg_match("/[A-Z]/",$_POST["password"])) || (!preg_match("/[a-z]/",$_POST["password"])) || (!preg_match("/[0-9]/",$_POST["password"])) || (strlen($_POST["password"]) < 8)) {
				resetForm();
				echo "<br><font color='red'>The password must meet the above criteria</font><br>";
			} else {
				require_once('dbconnect.php');
				$userName = $db->quote($_SESSION["username"]);
				$password = $db->quote(md5($_POST['password']));
				try {
					$db->exec("UPDATE user SET password = $password WHERE userID = $userName");
					echo "<SCRIPT>alert('You have successfully reset your password - Please login again.');window.location.href='logout.php'</SCRIPT>";
				} catch (PDOException $e) {
					echo "Database error resetting your password<br>" . $e->getMessage();
					echo "<br>Please contact an administrator";
				}
			}
		}
		
	?>
	
	</div>
	
  </body>
  
</html>