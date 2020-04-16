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
			
			session_start();
			
			function displayForm() {
				echo "<div style='width:400px;'>
						  <form name='input' action='forgotten1.php' method='POST'>
						  <label style='float:left'>Security Question: </label><select name='question'
						                             style='float:right;clear:right;width:244px'>
												<option value='1'>What's your favourite colour?</option>
												<option value='2'>Where were you born?</option>
												<option value='3'>What's your favourite animal?</option>
											 </select>
						  <br><br>
						  <label style='float:left'>Security Answer: </label><input type='text'
												  name='answer'
												  style='float:right;clear:right;width:240px'/>
						  <br><br>
						  <input type='hidden'
								 name='submitted'
								 value='true'/>
						  <input type='submit'
								 value='Next'/>
						</form>
					</div>";
			}
			
			if ($_SESSION["reset"] == "true") {
				if (!isset($_POST["submitted"])) {
					displayForm();
				}
			
			
				if (isset($_POST["submitted"])) {
					if (!empty($_POST["answer"])) {
						require_once('dbconnect.php');
						$question = $_POST["question"];
						$answer   = $_POST["answer"];
						$uID      = $_SESSION["uID"];
						$result   = $db->query("SELECT userID, questionID, sec_ans FROM user WHERE userID = $uID");
						$row = $result->fetch();
						if (($question == $row["questionID"]) && (strtoupper($answer) == strtoupper($row["sec_ans"]))) {
							header('Location: reset.php');
							exit();
						} else {
							displayForm();
							echo "<br><font color='red'>Error - Your answer does not match our records.</font><br>";
							echo "<br><font color='red'>Please Try Again.</font><br>";
						}
					} else {
						displayForm();
						echo "<br><font color='red'>You haven't entered a valid value in the security answer field.</font><br>";
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