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
	    require('menu.php');
		echo "<br>";
		
		if (isset($_SESSION["username"])) {
			include('mainstyle.php');
			$user = $_SESSION['username'];
			
			require_once('dbconnect.php');
			echo "
				<div class=main>
			";
			
			if (!preg_match("/^[0-9]*$/",$_GET['adoptID'])) {
				echo "GET Variable Injection Detected";
			} else {
			
				$adoptionID = $_GET['adoptID'];
				
				if (!isset($_POST["submit"])) {
					
					$result = $db->query("SELECT userID, animalID, approved FROM adoption_request WHERE adoptionID = $adoptionID");
					$count = $result->rowCount();
					$row = $result->fetch();
					
					if ($count == 0) {
						echo "No Adoption Request with that ID found.";
					} else {
						if ($row['userID'] !== $user) {
							echo "You cannot cancel someone else's adoption request.";
						} elseif ($row['approved'] !== NULL) {
							echo "This request has already been processed.";
						} else {
							echo "
								<div width='400px'>
									<form name='input' action='cancelreq.php?adoptID=$adoptionID' method='POST'>
										<label type='float:left'>Are you sure you wish to cancel the adoption request?</label><br><br>
										<input type='hidden' name='animalID' value ='$row[animalID]'/>
										<input type='submit' name='submit' value='Yes'/>
										<input type='submit' name='submit' value='No'/>
									</form>
								</div>
							";
						}
					}
				} else {
					if ($_POST["submit"] == 'Yes') {
						try {
							$animalID = $_POST["animalID"];
							$db->exec("UPDATE adoption_request SET approved = 2 WHERE adoptionID = '$adoptionID'");
							$db->exec("UPDATE animal SET available = 1 WHERE animalID = '$animalID'");
							echo "<SCRIPT>alert('Your adoption request has been successfully cancelled.');window.location.href='home.php'</SCRIPT>";
						} catch (PDOException $e) {
							echo "Error cancelling your adoption request:";
							echo $e->getMessage();
							echo "<br>Please contact an administrator";
						}
					} else {
						Header('Location: home.php');
						exit();
					}
				}
				
				echo "
					</div>
				";
			}
		} else {
			header('Location: login.php');
			exit();
		}
	?>
	
  </body>
  
</html>