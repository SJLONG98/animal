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
		
		function animalSearch() {
			echo "
				<u><h4>Search for an Animal:</h4></u>
			
				<div width='400px'>
					<form name='input' action='removeanimal.php' method='POST'>
						<label type='float:left'>Animal ID:</label>
						<input type='text' name='id' style='float:right;clear:right;width:200px'
							value='";if(isset($_POST['id'])) {echo htmlspecialchars($_POST['id']);}echo"'/>
						<br><br>
						<label type='float:left'>Animal Name:</label>
						<input type='text' name='name' style='float:right;clear:right;width:200px'
							value='";if(isset($_POST['name'])) {echo htmlspecialchars($_POST['name']);}echo"'/>
						<br><br>
						<input type='hidden' name='search' value='true'/>
						<input type='submit' value='Search'/>
					</form>
				</div>
			";
		}
		$flag = 1;
		
		if ((!isset($_POST['search'])) && (!isset($_POST['animalID']))) {
			animalSearch();
		} elseif ((isset($_POST['search'])) && (!isset($_POST['animalID']))) {
			if ((empty($_POST["name"])) && (empty($_POST["id"])) ) {
				animalSearch();
				echo "<br><font color='red'>You haven't entered a value in any of the fields.</font><br>";
				$flag = 0;
			} else {
				require_once('dbconnect.php');
				if (!empty($_POST["name"])) {
					$animalName  = $db->quote(htmlspecialchars(strtoupper($_POST["name"])));
					$result = $db->query("SELECT animalID, available FROM animal WHERE UPPER(name) = $animalName");
					$row = $result->fetch();
					if ($result->rowCount() == 0) {
						animalSearch();
						echo "<br><font color='red'>Error - The animal name entered does not exist.</font><br>";
						$flag = 0;
					} elseif ($row['available'] == 0) {
						animalSearch();
						echo "<br><font color='red'>Error - You cannot delete an adopted animal.</font><br>";
						$flag = 0;
					} else {
						echo "
							<div width='400px'>
								<form name='input' action='removeanimal.php' method='POST'>
									<label type='float:left'>Are you sure you wish to delete $animalName?</label><br><br>
									<input type='hidden' name='animalID' value='$row[animalID]'/>
									<input type='submit' name='submit' value='Yes'/>
									<input type='submit' name='submit' value='No'/>
								</form>
							</div>
						";
					}
				} else {
					$animalID = $db->quote(htmlspecialchars($_POST["id"]));
					$result = $db->query("SELECT name, available FROM animal WHERE animalID = $animalID");
					$row = $result->fetch();
					if ($result->rowCount() == 0) {
						animalSearch();
						echo "<br><font color='red'>Error - The animal ID entered does not exist.</font><br>";
						$flag = 0;
					} elseif ($row['available'] == 0) {
						animalSearch();
						echo "<br><font color='red'>Error - You cannot delete an adopted animal.</font><br>";
						$flag = 0;
					} else {
						echo "
							<div width='400px'>
								<form name='input' action='removeanimal.php' method='POST'>
									<label type='float:left'>Are you sure you wish to delete '$row[name]'?</label><br><br>
									<input type='hidden' name='animalID' value=$animalID/>
									<input type='submit' name='submit' value='Yes'/>
									<input type='submit' name='submit' value='No'/>
								</form>
							</div>
						";
					}
				}
				
			}
		} elseif (isset($_POST['animalID'])) {
			if ($_POST['submit'] == 'Yes') {
				require_once('dbconnect.php');
				$animalID = $_POST['animalID'];
				$db->exec("DELETE FROM animal WHERE animalID = '$animalID'");
				echo "<SCRIPT>alert('You have successfully deleted the animal.');window.location.href='staffhome.php'</SCRIPT>";
			} elseif ($_POST['submit'] == 'No') {
				animalSearch();
			}
		}
		
	?>
	
	</div>
	
  </body>
  
</html>