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
	?>
	
	<div class=main>
	<u><h4>Add an Animal:</h4></u>
	
		<div width='400px'>
			<form name='input' action='addanimal.php' method='POST' id='addanimal' enctype='multipart/form-data'>
				<label type='float:left'>Animal Name:</label>
				<input type='text' name='name' style='float:right;clear:right;width:200px'
					value='<?php if(isset($_POST["name"])) {echo htmlspecialchars($_POST["name"]);}?>'/>
				<br><br>
				<label type='float:left'>Animal Type:</label>
				<input type='text' name='type' style='float:right;clear:right;width:200px;'
					value='<?php if(isset($_POST["type"])) {echo htmlspecialchars($_POST["type"]);}?>'/>
				<br><br>
				<label type='float:left'>Animal DOB:</label>
				<input type='date' name='dob' style='float:right;clear:right;width:199px;'
					value='<?php if(isset($_POST["dob"])) {echo $_POST["dob"];}?>'/>
				<br><br>
				<label type='float:left'>Animal Description:</label>
				<textarea rows='4' name='desc' style='float:right;clear:right;width:198px;height:60px;' form='addanimal'/><?php if(isset($_POST['submitted'])) { echo htmlspecialchars($_POST["desc"]);}?></textarea>
				<br><br><br>
				<label type='float:left'><br>Animal Photo:</label>
				<input type='file' name='photo' style='float:right;clear:right;width:204px;' id='photo'/>
				<br><br>
				<input type='hidden' name='submitted' value='true'/>
				<input type='submit' value='Add Animal'/>
			</form>
		</div>
	
		<?php
			$flag = 1;
			
			if (isset($_POST['submitted'])) {
				if (empty($_POST["name"])) {
					echo "<br><font color='red'>You haven't entered a value in the animal name field.</font><br>";
					$flag = 0;
				}
				if (empty($_POST["type"])) {
					echo "<br><font color='red'>You haven't entered a value in the animal type field.</font><br>";
					$flag = 0;
				}
				if (empty($_POST["dob"])) {
					echo "<br><font color='red'>You haven't entered a value in the animal DOB field.</font><br>";
					$flag = 0;
				}
				if (empty($_POST["desc"])) {
					echo "<br><font color='red'>You haven't entered a value in the animal desc field.</font><br>";
					$flag = 0;
				}
				if ($_POST["dob"] >= date("Y-m-d")) {
					echo "<br><font color='red'>You have entered a future value in the animal DOB field.</font><br>";
					$flag = 0;
				}
				//Start file work
				if (!empty($_FILES['photo']['name'])) {
					$dir = 'img/';
					$file = $dir . basename($_FILES['photo']['name']);
					
					$fileType = strtolower(pathinfo($file,PATHINFO_EXTENSION));
					$sizeArray = getimagesize($_FILES['photo']['tmp_name']);
					
					if ($sizeArray == false) {
						echo "<br><font color='red'>You haven't uploaded an image file.</font><br>";
						$flag = 0;
					}
					if (file_exists($file)) {
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
						echo "<br><font color='red'>The uploaded image file exceeds 500KB.</font><br>";
						$flag = 0;
					}
					if ($fileType != "jpg" &&
					    $fileType != "jpeg" &&
						$fileType != "png" &&
						$fileType != "gif"
						) {
							echo "<br><font color='red'>Only JPG, JPEG, PNG & GIF formats are allowed.</font><br>";
							$flag = 0;
					}
				}
				
				if ($flag == 1) {
					//No empty fields so start DB
					require_once('dbconnect.php');
					$animalName  = $db->quote(htmlspecialchars($_POST["name"]));
					$animalType  = $db->quote(htmlspecialchars($_POST["type"]));
					$animalDOB   = $db->quote($_POST["dob"]);
					$animalDesc  = $db->quote(htmlspecialchars($_POST["desc"]));
					
					if (!empty($_FILES['photo']['name'])) {
						$animalPhoto = $file;
						if (move_uploaded_file($_FILES['photo']['tmp_name'],$file)) {
							try {
								$sql = "INSERT INTO animal (animalID, name, type, date_of_birth, description, photo_link, available) VALUES";
								$sql = $sql . " (default,$animalName,$animalType,$animalDOB,$animalDesc,'$animalPhoto','1')";
								$db->exec($sql);
								
								$result   = $db->query("SELECT a.animalID FROM animal a WHERE a.available = 1 AND NOT EXISTS (SELECT 1 FROM owns WHERE animalID = a.animalID)");
								$row = $result->fetch();
								
								$sql = "INSERT INTO owns (ownID, userID, animalID) VALUES";
								$sql = $sql . " (default,'staff','$row[animalID]')";
								$db->exec($sql);
								
								echo "<SCRIPT>alert('Your animal has been successfully added.');window.location.href='addanimal.php'</SCRIPT>";
							} catch(PDOException $e) {
								echo "<br><font color='red'>Database error adding animal - " . $e->getMessage() . "</font><br>";
								echo "<br><font color='red'>Please contact an administrator</font><br>";
							}
						} else {
							echo "<br><font color='red'>Error adding animal - " . "Moving file from tmp to filesystem" . "</font><br>";
							echo "<br><font color='red'>Please contact an administrator</font><br>";
						}
					} else {
						try {
							$sql = "INSERT INTO animal (animalID, name, type, date_of_birth, description, photo_link, available) VALUES";
							$sql = $sql . " (default,$animalName,$animalType,$animalDOB,$animalDesc,null,'1')";
							$db->exec($sql);
							echo "<SCRIPT>alert('Your animal has been successfully added.');window.location.href='addanimal.php'</SCRIPT>";
						} catch(PDOException $e) {
							echo "<br><font color='red'>Database error adding animal - " . $e->getMessage() . "</font><br>";
							echo "<br><font color='red'>Please contact an administrator</font><br>";
						}
					}
					
				}
			}
		?>
	
	</div>
	
  </body>
  
</html>