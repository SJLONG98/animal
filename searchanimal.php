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
			require('menu.php');
			echo "<br>";
			include('mainstyle.php');
			echo "
				<style>
					img {
						width:175px;
					}
					table, th, td {
						width:1000px;
						border: 1px solid black;
						border-collapse: collapse;
					}
					th, td {
						width:175px;
						padding-left: 5px;
						padding-right: 20px;
					}
					th {
						text-align: left;
					}
					th.statage, td.statage {
						width:100px;
						padding-left: 5px;
						padding-right: 20px;
					}
					th.more, td.more {
						width:125px;
						padding-left: 5px;
						padding-right: 20px;
					}
				</style>
			";
		} else {
			header('Location: login.php');
			exit();
		}
	
	
		echo "<div class=main>";
		
		function animalSearch() {
			echo "
				<u><h4>Search for an Animal:</h4></u>
			
				<div width='400px'>
					<form name='input' action='searchanimal.php' method='POST'>
						<label type='float:left'>Animal ID:</label>
						<input type='text' name='id' style='float:right;clear:right;width:200px'
							value='";if(isset($_POST['id'])) {echo htmlspecialchars($_POST['id']);}echo"'/>
						<br><br>OR<br><br>
						<label type='float:left'>Animal Name:</label>
						<input type='text' name='name' style='float:right;clear:right;width:200px'
							value='";if(isset($_POST['name'])) {echo htmlspecialchars($_POST['name']);}echo"'/>
						<br><br>
						<label type='float:left'>Animal Type:</label>
						<input type='text' name='type' style='float:right;clear:right;width:200px'
							value='";if(isset($_POST['type'])) {echo htmlspecialchars($_POST['type']);}echo"'/>
						<br><br>
						<label type='float:left'>Animal Age:</label>
						<input type='text' name='age' style='float:right;clear:right;width:200px'
							value='";if(isset($_POST['age'])) {echo htmlspecialchars($_POST['age']);}echo"'/>
						<br><br>
						<input type='hidden' name='search' value='true'/>
						<input type='submit' value='Search'/>
					</form>
				</div>
			";
		}
		
		function populateSearch($results) {
			echo "
				<table>
					<tr>
						<th>Name</th>
						<th>Type</th>
						<th>Photo</th>
						<th class=statage>Status</th>
						<th class=statage>Age</th>
						<th class=more>More</th>
					</tr>
					";
					foreach ($results as $row) {
						if (empty($row['photo_link'])) {
							$photo = 'Not Available';
						} else {
							$photo = '<img src="' . $row['photo_link'] . '">';
						}
						echo "
							<tr>
								<td>$row[name]</td>
								<td>$row[type]</td>
								<td>$photo</td>
						";
						
						if ($row['available'] == 1) {
							echo "
								<td class=statage>Available</td>
							";
						} else {
							echo "
								<td class=statage>Not Available</td>
							";
						}
						
						echo "
								<td class=statage>$row[age]</td>
								<td class=more><a href='animal.php?animalID=$row[animalID]'>More Details</a></td>
							</tr>
						";
					};
					echo "
				</table>
				";
		}
		
		if ((!isset($_POST['search'])) && (!isset($_POST['animalID']))) {
			animalSearch();
		} elseif ((isset($_POST['search'])) && (!isset($_POST['animalID']))) {
			if ((empty($_POST["name"])) && (empty($_POST["id"])) && (empty($_POST["type"])) && ($_POST["age"] == "") ) {
				animalSearch();
				echo "<br><font color='red'>You haven't entered a value in any of the fields.</font><br>";
			} else {
				require_once('dbconnect.php');
				if (!empty($_POST["id"])) {
					$animalID  = $db->quote(htmlspecialchars($_POST["id"]));
					$results = $db->query("SELECT animalID, name, type, photo_link, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) as age, available FROM animal WHERE animalID LIKE $animalID");
					if ($results->rowCount() == 0) {
						animalSearch();
						echo "<br><font color='red'>Error - The animal ID entered does not match any records.</font><br>";
					} else {
						echo "Search result for Animal ID $animalID:";
						populateSearch($results);
					}
				} else {
					if (!empty($_POST["name"])) {
						$exactAnimalName = $db->quote(htmlspecialchars(strtoupper($_POST["name"])));
						$animalName  = $db->quote(htmlspecialchars(strtoupper("%".$_POST["name"]."%")));
					} else {
						$animalName = "NULL";
						$exactAnimalName = "NULL";
					}
					if (!empty($_POST["type"])) {
						$exactAnimalType = $db->quote(htmlspecialchars(strtoupper($_POST["type"])));
						$animalType = $db->quote(htmlspecialchars(strtoupper("%".$_POST["type"]."%")));
					} else {
						$animalType = "NULL";
						$exactAnimalType = "NULL";
					}
					if ($_POST["age"] != "") {
						$animalAge = $db->quote(htmlspecialchars($_POST["age"]));
					} else {
						$animalAge = "NULL";
					}

					$sqlSelect = "SELECT animalID, name, type, photo_link, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) as age, available FROM animal ";
					
					$exact1 = "WHERE UPPER(name) = IFNULL($exactAnimalName,UPPER(name)) ";
					$exact2 = "AND UPPER(type) = IFNULL($exactAnimalType,UPPER(type)) ";
					
					$like1 = "WHERE UPPER(name) LIKE IFNULL($animalName,UPPER(name)) ";
					$like2 = "AND UPPER(type) LIKE IFNULL($animalType,UPPER(type)) ";
					
					$where3 = "AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) = IFNULL($animalAge,TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()))";
					
					$sql1 = $sqlSelect . $exact1 . $exact2 . $where3;
					$sql2 = $sqlSelect . $like1 . $like2 . $where3;
					
					$results = $db->query($sql1);
					if ($results->rowCount() == 0) {
						$results = $db->query($sql2);
						if ($results->rowCount() == 0) {
							animalSearch();
							echo "<br><font color='red'>Error - The animal fields entered do not match any records.</font><br>";
						} else {
							echo "No exact Search Results found.<br><br>";
							echo "Similar search results:";
							populateSearch($results);
						}
					} else {
						echo "Search Results:";
						populateSearch($results);
					}
				}
			}
		}
		
	?>
	
	</div>
	
  </body>
  
</html>