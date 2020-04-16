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
		Please use our form below to register at our sanctuary.
		<hr style="width:400px;">
		Note: Your password must be at least 8 characters<br> in length including a number and a capital letter.
		<hr style="width:400px;">
		<br>
		<form name='input' action='register.php' method='POST'>
		  <div style="width:400px;">
			  <label style="float:left">Username: </label><input type='text'
							   name='username'
							   placeholder='e.g. username1'
							   autocomplete='on' autofocus
							   value='<?php if(isset($_POST["username"])) {echo htmlspecialchars($_POST["username"]);}?>'
							   style="float:right;clear:right;width:240px"/>
			  <br><br>
			  <label style="float:left">Password: </label><input type='password'
								name='password'
								style="float:right;clear:right;width:240px"/>
			  <br><br>
			  <label style="float:left">Confirm Password: </label><input type='password'
									   name='password2'
									   style="float:right;clear:right;width:240px"/>
			  <br><br>
			  <label style="float:left">Email Address: </label><input type='text'
									   name='email'
									   placeholder='somebody@someplace.com'
									   value='<?php if(isset($_POST["email"])) {echo htmlspecialchars($_POST["email"]);}?>'
									   style="float:right;clear:right;width:240px"/>
			  <br><br>
			  <label style="float:left">Confirm Email: </label><input type='text'
									   name='email2'
									   placeholder='somebody@someplace.com'
									   style="float:right;clear:right;width:240px"/>
			  <br><br>
			  <label style="float:left">Security Question: </label>
			                           <select name="question"
			                                   style="float:right;clear:right;width:244px">
											<option value="1">What's your favourite colour?</option>
											<option value="2">Where were you born?</option>
											<option value="3">What's your favourite animal?</option>
									   </select>
			  <br><br>
			  <label style="float:left">Security Answer: </label><input type='text'
									   name='answer'
									   placeholder='Red'
									   style="float:right;clear:right;width:240px"/>
			  <br><br>
			  <input type='hidden'
					 name='submitted'
					 value='true'/>
			  <input type='submit'
					 value='Register!'/>
		  </div>
		</form>
		
		<?php
			$flag = 1;
			if (isset($_POST["submitted"])) {
				if (empty($_POST["username"])) {
					echo "<br><font color='red'>You haven't entered a valid value in the username field.</font><br>";
					$flag = 0;
				}
				if (empty($_POST["password"])) {
					echo "<br><font color='red'>You haven't entered a valid value in the password field.</font><br>";
					$flag = 0;
				} else if (empty($_POST["password2"])) {
					echo "<br><font color='red'>You haven't entered a valid value in the confirm password field.</font><br>";
					$flag = 0;
				} else if ((!empty($_POST["password2"])) && (!empty($_POST["password"]))) {
					if ($_POST["password"] != $_POST["password2"]) {
						echo "<br><font color='red'>Your passwords do not match.</font><br>";
						$flag = 0;
					} else {
						if ((!preg_match("/[A-Z]/",$_POST["password"]))
						|| (!preg_match("/[a-z]/",$_POST["password"]))
						|| (!preg_match("/[0-9]/",$_POST["password"]))
						|| (strlen($_POST["password"]) < 8)) {
							echo "<br><font color='red'>Your password does not meet the requirements.</font><br>";
							$flag = 0;
						}
					}
				}
				if (empty($_POST["email"])) {
					echo "<br><font color='red'>You haven't entered a valid value in the email field.</font><br>";
					$flag = 0;
				} else if (empty($_POST["email2"])) {
					echo "<br><font color='red'>You haven't entered a valid value in the confirm email field.</font><br>";
					$flag = 0;
				} else if ((!empty($_POST["email"])) && (!empty($_POST["email2"]))) {
					if ($_POST["email"] != $_POST["email2"]) {
						echo "<br><font color='red'>Your email addresses do not match.</font><br>";
						$flag = 0;
					} else {
						if (!preg_match("/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/",$_POST["email"])) {
							echo "<br><font color='red'>Your email address does not meet the requirements.</font><br>";
							$flag = 0;
						}
					}
				}
				if (empty($_POST["answer"])) {
					echo "<br><font color='red'>You haven't entered a value in the security answer field.</font><br>";
					$flag = 0;
				}
				if ($flag = 1) {
					//No empty/invalid fields
					require_once('dbconnect.php');
					$username = $db->quote(htmlspecialchars($_POST["username"]));
					$password = md5($_POST["password"]);
					$email    = $db->quote(htmlspecialchars($_POST["email"]));
					$staff    = 0;
					$question = $_POST["question"];
					$answer   = $db->quote(htmlspecialchars($_POST["answer"]));
					
					$result   = $db->query("SELECT userID FROM user WHERE userID = $username");
					if ($result->rowCount() > 0) {
						echo "<br><font color='red'>The username entered already exists</font><br>";
						$flag = 0;
					} else {
						try {
							$db->exec("INSERT INTO user VALUES($username,$email,'$password',$staff,$question,$answer)");
							$result2 = $db->query("SELECT userID FROM user WHERE userID = $username");
							$row = $result2->fetch();
							session_start();
							$_SESSION["username"] = $row['userID'];
							$_SESSION["staff"]    = "false";
							header('Location: home.php');
							exit();
						}
						catch(PDOException $e) {
							echo "Database error registering you as a new user<br>" . $e->getMessage();
							echo "<br>Please contact an administrator";
						}
					}
				}
				
			}
			
		?>
		
		<hr style="width:400px;">
		<a href="login.php">Return Home</a>
        </div>
	</p>
  </body>
</html>