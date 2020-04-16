<!DOCTYPE html>
<html>
  <head>
    <title>Angus' Animal Sanctuary</title
  </head>
  
  <body>
  
	<p>
		<?php
	      include('header.php');
	    ?>
		<div align='center'>
		Please use our login form below to visit our sanctuary.<br><br>
		<form name='input' action='login.php' method='POST'>
		  Username: <input type='text'
		                   name='username'
		                   size='20'
						   placeholder='e.g. username1'
						   autocomplete='on' autofocus
		                   value='<?php if(isset($_POST["username"])) {echo htmlspecialchars($_POST["username"]);}?>'/>
		  <br><br>
		  Password:  <input type='password'
		                    name='password'
							size='20'/><br><br>
		  <input type='hidden'
		         name='submitted'
				 value='true'/>
		  <input type='submit'
		         value='Login'/>
		</form>
  
  <?php
		
    if (isset($_POST["submitted"])) {
		if (!empty($_POST["username"])) {
			if (!empty($_POST["password"])) {
				$username = htmlspecialchars($_POST["username"]);
				require_once('dbconnect.php');
				$username = $db->quote($username);
				$password = md5($_POST["password"]);
				$result = $db->query("SELECT userID, password, staff FROM user WHERE userID = $username");
				if ($result->rowCount() > 0) {
					$row = $result->fetch();
					if ($row["password"] == $password) {
						session_start();
						$_SESSION["username"] = $row["userID"];
						if ($row["staff"] == 1) {
							$_SESSION["staff"] = "true";
						} else {
							$_SESSION["staff"] = "false";
						}
						header('Location: loggedin.php');
						exit();
					} else {
						echo "<br><font color='red'>Error - The password entered is invalid.</font><br>";
					}
				} else {
					echo "<br><font color='red'>Error - The username entered does not exist.</font><br>";
				}
			} else {
				echo "<br><font color='red'>You haven't entered a valid value in the password field.</font><br>";
			}
		} else {
			echo "<br><font color='red'>You haven't entered a valid value in the username field.</font><br>";
		}
	}
    
  ?>
		<hr style="width:400px;">
        <a href='forgotten.php'>I've forgotten my password!</a><br><br>
		If you are not a member then please click the <a href='register.php'>register</a> link.
		<hr style="width:400px;">
	    </div>
	</p>
  </body>
</html>