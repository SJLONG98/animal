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
			
			if (!preg_match("/^[0-9]*$/",$_GET['animalID'])) {
				echo "GET Variable Injection Detected";
			} else {
			
				$animalID = $_GET['animalID'];
				
				if (!isset($_POST["submit"])) {
					
					$result = $db->query("SELECT userID FROM adoption_request WHERE animalID = $animalID AND approved IS NULL");
					$count = $result->rowCount();
					$row = $result->fetch();
					
					if (($count !== 0) && ($row['userID'] == $user)) {
						echo "You have already requested to adopt this animal";
					} elseif ($count !== 0) {
						echo "Sorry, another user has requested to adopt this animal";
					} else {
						// Check if animal is already adopted
						$result = $db->query("SELECT adoptionID, userID FROM adoption_request WHERE animalID = $animalID AND approved = 1");
						$count = $result->rowCount();
						$row = $result->fetch();
						
						if (($count !== 0) && ($row['userID'] == $user)) {
							echo "You have already adopted this animal";
						} elseif ($count !== 0) {
							echo "Sorry, another user has adopted this animal";
						} else {
							echo "
								<div width='400px'>
									<form name='input' action='adopt.php?animalID=$animalID' method='POST'>
										<label type='float:left'>Are you sure you wish to adopt the animal?</label><br><br>
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
							$sql = "INSERT INTO adoption_request (adoptionID, userID, animalID, approved) VALUES";
							$sql = $sql . " (default,'$user','$animalID',null)";
							$db->exec($sql);
							$db->exec("UPDATE animal SET available = 0 WHERE animalID = '$animalID'");
							echo "<SCRIPT>alert('Your adoption request has been successfully submitted.');window.location.href='useradoptrequests.php'</SCRIPT>";
						} catch (PDOException $e) {
							echo "Error cancelling your adoption request:";
							echo $e->getMessage();
							echo "<br>Please contact an administrator";
						}
					} else {
						Header('Location: animal.php?animalID='.$animalID);
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