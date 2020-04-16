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
			if ($_SESSION["staff"] == "true") {
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
						
						$result = $db->query("SELECT userID, animalID, approved FROM adoption_request WHERE adoptionID = '$adoptionID'");
						$count = $result->rowCount();
						$row = $result->fetch();
						
						if ($count == 0) {
							echo "No Adoption Request with that ID found.";
						} else {
							if ($row['userID'] == $user) {
								echo "You cannot approve your own adoption request.";
							} elseif ($row['approved'] !== NULL) {
								echo "This request has already been processed.";
							} else {
								echo "
									<div width='400px'>
										<form name='input' action='approvereq.php?adoptID=$adoptionID' method='POST'>
											<label type='float:left'>Are you sure you wish to approve the adoption request?</label><br><br>
											<input type='hidden' name='animalID' value ='$row[animalID]'/>
											<input type='hidden' name='userID' value ='$row[userID]'/>
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
								$userID = $_POST["userID"];
								$db->exec("UPDATE adoption_request SET approved = 1 WHERE adoptionID = '$adoptionID'");
								$db->exec("UPDATE owns SET userID = '$userID' WHERE animalID = '$animalID'");
								echo "<SCRIPT>alert('The adoption request has been successfully approved.');window.location.href='alladoptrequests.php'</SCRIPT>";
							} catch (PDOException $e) {
								echo "Error approving the adoption request:";
								echo $e->getMessage();
								echo "<br>Please contact an administrator";
							}
						} else {
							Header('Location: adoptrequests.php');
							exit();
						}
					}
					
					echo "
						</div>
					";
				}
			} else {
				header('Location: home.php');
				exit();
			}
		} else {
			header('Location: login.php');
			exit();
		}
	?>
	
  </body>
  
</html>