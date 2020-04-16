<!DOCTYPE html>
<html>
  <head>
    <title>Angus' Animal Sanctuary</title>
  </head>
  
  <body>
  
	<p>
		<?php
	      include('header.php');
	    ?>
		<div align='center'>
		Please use our forgotten password form to reset your password
		<hr style="width:400px;">
		
		<?php
			
			session_start();
			
			function displayForm() {
				echo "Note: Your password must be at least 8 characters<br> in length including a number and a capital letter.<br><br>";
				echo "<div style='width:400px;'>
						  <form name='input' action='reset.php' method='POST'>
						  <label style='float:left'>Password: </label><input type='password'
										   name='password'
										   style='float:right;clear:right;width:240px'/>
						  <br><br>
						  <label style='float:left'>Confirm Password: </label><input type='password'
												   name='confirm'
												   style='float:right;clear:right;width:240px'/>
						  <br><br>
						  <input type='hidden'
								 name='submitted'
								 value='true'/>
						  <input type='submit'
								 value='Reset'/>
						</form>
					</div>";
			}
			
			if ($_SESSION["reset"] == "true") {
				if (!isset($_POST["submitted"])) {
					displayForm();
				}
			
			
				if (isset($_POST["submitted"])) {
					if (!empty($_POST["password"])) {
						if (!empty($_POST["confirm"])) {
							if ($_POST["password"] == $_POST["confirm"]) {
								if ((!preg_match("/[A-Z]/",$_POST["password"])) || (!preg_match("/[a-z]/",$_POST["password"])) || (!preg_match("/[0-9]/",$_POST["password"])) || (strlen($_POST["password"]) < 8)) {
									displayForm();
									echo "<br><font color='red'>Error - Your passwords do not meet the above criteria.</font><br>";
								} else {
									$password = md5($_POST["password"]);
									$uID = $_SESSION["uID"];
									require_once('dbconnect.php');
									try {
										$db->exec("UPDATE user SET password = '$password' WHERE userID = $uID");
										session_destroy();
										echo "<SCRIPT>alert('Your password has been successfully reset - Please login.');window.location.href='login.php'</SCRIPT>";
										exit();
									}
									catch(PDOException $e) {
										echo "Database error updating your password<br>" . $e->getMessage();
										echo "<br>Please contact an administrator";
									}
								}
							} else {
								displayForm();
								echo "<br><font color='red'>Error - Your passwords do not match.</font><br>";
							}
						} else {
							displayForm();
							echo "<br><font color='red'>You haven't entered a valid value in the confirm password field.</font><br>";
						}
					} else {
						displayForm();
						echo "<br><font color='red'>You haven't entered a valid value in the password field.</font><br>";
					}
				}
			} else {
				header ('Location: forgotten.php');
				session_destroy();
				exit();
			}
			
		?>
		
		<hr style="width:400px;">
		<a href="login.php">Return Home</a><br><br>
		If you are not a member then please click the <a href='register.php'>register</a> link.
		<hr style="width:400px;">
	    </div>
	</p>
  </body>
</html>