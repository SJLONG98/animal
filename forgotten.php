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
		<hr style="width:400px;"><br>
		<?php
			
			function displayForm() {
				echo "
					<form name='input' action='forgotten.php' method='POST'>
						Username: <input type='text'
									     name='username'
									     size='20'
									     placeholder='e.g. username1'
									     autocomplete='on' autofocus/>
						<br><br>
					    <input type='hidden'
							   name='submitted'
							   value='true'/>
					    <input type='submit'
							   value='Reset'/>
					</form>
				";
			}
			
			if (!isset($_POST["submitted"])) {
				displayForm();
			}
		
		
			if (isset($_POST["submitted"])) {
				if (!empty($_POST["username"])) {
					require_once('dbconnect.php');
					$username = $db->quote(htmlspecialchars($_POST["username"]));
					$result = $db->query("SELECT userID FROM user WHERE userID = $username");
					if ($result->rowCount() > 0) {
						session_start();
						$_SESSION["reset"] = "true";
						$_SESSION["uID"] = $username;
						header ('Location: forgotten1.php');
						exit();
					} else {
						displayForm();
						echo "<br><font color='red'>Error - The username entered does not exist.</font><br>";
					}
				} else {
					displayForm();
					echo "<br><font color='red'>You haven't entered a valid value in the username field.</font><br>";
				}
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