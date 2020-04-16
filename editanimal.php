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
		
		echo "
			<div class=main>
			<u><h4>Edit an Animal:</h4></u>
		";
		
		function displayDBForm($animal) {
			require_once('dbconnect.php');
			
			$result   = $db->query("SELECT name, type, date_of_birth, description, photo_link FROM animal WHERE animalID = $animal");
			
			if ($result->rowCount() == 0) {
				echo "No animal exists with that ID";
			} else {
				$row = $result->fetch();
				echo "
					<div width='400px'>
						<form name='input' action='editanimal.php?animalID=$animal' method='POST' id='editanimal' enctype='multipart/form-data'>
							<label type='float:left'>Animal Name:</label>
							<input type='text' name='name' style='float:right;clear:right;width:200px'
								value='$row[name]'/>
							<br><br>
							<label type='float:left'>Animal Type:</label>
							<input type='text' name='type' style='float:right;clear:right;width:200px;'
								value='$row[type]'/>
							<br><br>
							<label type='float:left'>Animal DOB:</label>
							<input type='date' name='dob' style='float:right;clear:right;width:199px;'
								value='$row[date_of_birth]'/>
							<br><br>
							<label type='float:left'>Animal Description:</label>
							<textarea rows='4' name='desc' style='float:right;clear:right;width:198px;height:60px;' form='editanimal'>$row[description]</textarea>
							<br><br><br>
							<label type='float:left'><br>Animal Photo:</label>
							<input type='file' name='photo' style='float:right;clear:right;width:204px;' id='photo'/>
							<br><br>
							<input type='hidden' name='submitted' value='true'/>
							<input type='submit' value='Update Animal'/>
						</form>
					</div>
				";
			}
		}
		
		function displayForm($animal) {
			echo "
				<div width='400px'>
					<form name='input' action='editanimal.php?animalID=$animal' method='POST' id='editanimal' enctype='multipart/form-data'>
						<label type='float:left'>Animal Name:</label>
						<input type='text' name='name' style='float:right;clear:right;width:200px'
							value='$_POST[name]'/>
						<br><br>
						<label type='float:left'>Animal Type:</label>
						<input type='text' name='type' style='float:right;clear:right;width:200px;'
							value='$_POST[type]'/>
						<br><br>
						<label type='float:left'>Animal DOB:</label>
						<input type='date' name='dob' style='float:right;clear:right;width:199px;'
							value='$_POST[dob]'/>
						<br><br>
						<label type='float:left'>Animal Description:</label>
						<textarea rows='4' name='desc' style='float:right;clear:right;width:198px;height:60px;' form='editanimal'>$_POST[desc]</textarea>
						<br><br><br>
						<label type='float:left'><br>Animal Photo:</label>
						<input type='file' name='photo' style='float:right;clear:right;width:204px;' id='photo'/>
						<br><br>
						<input type='hidden' name='submitted' value='true'/>
						<input type='submit' value='Update Animal'/>
					</form>
				</div>
			";
		}
		
		if (!preg_match("/^[0-9]*$/",$_GET['animalID'])) {
			echo "GET Variable Injection Detected";
		} else {
			
			$animalID = $_GET['animalID'];
		
			if (!isset($_POST["submitted"])) {
				displayDBForm($animalID);
			} else {
				$flag = 1;
				
				if (empty($_POST["name"])) {
					displayForm($animalID);
					echo "<br><font color='red'>You haven't entered a value in the animal name field.</font><br>";
					$flag = 0;
				}
				if (empty($_POST["type"])) {
					displayForm($animalID);
					echo "<br><font color='red'>You haven't entered a value in the animal type field.</font><br>";
					$flag = 0;
				}
				if (empty($_POST["dob"])) {
					displayForm($animalID);
					echo "<br><font color='red'>You haven't entered a value in the animal DOB field.</font><br>";
					$flag = 0;
				}
				if (empty($_POST["desc"])) {
					displayForm($animalID);
					echo "<br><font color='red'>You haven't entered a value in the animal desc field.</font><br>";
					$flag = 0;
				}
				if ($_POST["dob"] >= date("Y-m-d")) {
					displayForm($animalID);
					echo "<br><font color='red'>You have entered a future value in the animal DOB field.</font><br>";
					$flag = 0;
				}
				if (!empty($_FILES['photo']['name'])) {
					$dir = 'img/';
					$file = $dir . basename($_FILES['photo']['name']);
					
					$fileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
					$sizeArray = getimagesize($_FILES['photo']['tmp_name']);
					
					if ($sizeArray == false) {
						displayForm($animalID);
						echo "<br><font color='red'>You haven't uploaded an image file.</font><br>";
						$flag = 0;
					}
					if (file_exists($file)) {
						displayForm($animalID);
						echo "<br><font color='red'>The image file uploaded already exists.</font><br>";
						for ($i = 1; $i < 11; $i++) {
							$type = "." . $fileType;
							$temp_file = basename($file,$type);
							$temp_file2 = $temp_file . "_$i" . $type;
							if (!file_exists($dir . $temp_file2)) {
								echo "<font color='green'>However, " . $temp_file2 . " is available.</font><br>";
								break;
							}
						}
						$flag = 0;
					}
					if ($_FILES['photo']['size'] > 500000) {
						displayForm($animalID);
						echo "<br><font color='red'>The uploaded image file exceeds 500KB.</font><br>";
						$flag = 0;
					}
					if ($fileType != "jpg" &&
						$fileType != "jpeg" &&
						$fileType != "png" &&
						$fileType != "gif"
						) {
							displayForm($animalID);
							echo "<br><font color='red'>Only JPG, JPEG, PNG & GIF formats are allowed.</font><br>";
							$flag = 0;
					}
				}
				
				if ($flag == 1) {
					require_once('dbconnect.php');
					$animalName  = $db->quote(htmlspecialchars($_POST["name"]));
					$animalType  = $db->quote(htmlspecialchars($_POST["type"]));
					$animalDOB   = $db->quote($_POST["dob"]);
					$animalDesc  = $db->quote(htmlspecialchars($_POST["desc"]));
					
					if (!empty($_FILES['photo']['name'])) {
						$animalPhoto = $file;
						if (move_uploaded_file($_FILES['photo']['tmp_name'],$file)) {
							try {
								$sql = "UPDATE animal SET name = $animalName, type = $animalType, date_of_birth = $animalDOB, description = $animalDesc, photo_link = '$animalPhoto' WHERE animalID = $animalID";
								$db->exec($sql);
								
								echo "<SCRIPT>alert('Your animal has been successfully updated.');window.location.href='addanimal.php'</SCRIPT>";
							} catch(PDOException $e) {
								echo "<br><font color='red'>Database error updating animal - " . $e->getMessage() . "</font><br>";
								echo "<br><font color='red'>Please contact an administrator</font><br>";
							}
						} else {
							echo "<br><font color='red'>Error updating animal - " . "Moving file from tmp to filesystem" . "</font><br>";
							echo "<br><font color='red'>Please contact an administrator</font><br>";
						}
					} else {
						try {
							$sql = "UPDATE animal SET name = $animalName, type = $animalType, date_of_birth = $animalDOB, description = $animalDesc WHERE animalID = $animalID";
							$db->exec($sql);
							echo "<SCRIPT>alert('Your animal has been successfully updated.');window.location.href='viewallanimals.php'</SCRIPT>";
						} catch(PDOException $e) {
							echo "<br><font color='red'>Database error updating animal - " . $e->getMessage() . "</font><br>";
							echo "<br><font color='red'>Please contact an administrator</font><br>";
						}
					}
				}
				
			}
		}
		
	?>
	
	</div>
	
  </body>
  
</html>