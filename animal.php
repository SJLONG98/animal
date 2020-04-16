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
				th.smaller, td.smaller {
					width:125px;
					padding-left: 5px;
					padding-right: 20px;
				}
				th.age, td.age{
					width:75px;
					padding-left: 5px;
					padding-right: 20px;
				}
				table.status {
					width:300px;
					border: 1px solid black;
					border-collapse: collapse;
				}
				
			</style>
		";
		
		if (isset($_SESSION["username"])) {
			
			$uID = $_SESSION["username"];
			include('mainstyle.php');
			
			if (!preg_match("/^[0-9]*$/",$_GET['animalID'])) {
				echo "<div class=main>GET Variable Injection Detected</div>";
			} else {
			
				$animalID = $_GET['animalID'];
					
				require_once('dbconnect.php');
				$result = $db->query("SELECT name, type, date_of_birth, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) as age, description, photo_link, available FROM animal WHERE animalID = '$animalID'");
				$count = $result->rowCount();
				$row = $result->fetch();
				
				echo "
					<div class=main><br>
					";
					if ($count == 0) {
						echo "
							Error - No animal matching that ID found.
						";
					} else {
						echo "
						Details for $row[name]:
						<table>
							<tr>
								<th class=smaller>Name</th>
								<th class=smaller>Type</th>
								<th>Date of Birth</th>
								<th class=age>Age</th>
								<th>Description</th>
								<th>Photo</th>
							</tr>
							";
							
							if (empty($row['photo_link'])) {
								$photo = 'Not Available';
							} else {
								$photo = '<img src="' . $row['photo_link'] . '">';
							}
							echo "
								<tr>
									<td class=smaller>$row[name]</td>
									<td class=smaller>$row[type]</td>
									<td>$row[date_of_birth]</td>
									<td class=age>$row[age]</td>
									<td>$row[description]</td>
									<td>$photo</td>
								</tr>
							";
							
							echo "
						</table>";
					}
					echo "
					<br><br>
					
					<table class=status>
						<tr>
							<th class=smaller>Status</th>
							<th class=smaller>Action</th>
						</tr>
						<tr>
					";
					
					if ($row['available'] == 1) {
						echo "
							<td class=smaller>Available</td>
							<td class=smaller><a href='adopt.php?animalID=$animalID'>Adopt</a></td>
						";
					} else {
						echo "
							<td class=smaller>Not Available</td>
							<td class=smaller>None</td>
						";
					}
					
					echo "
						</tr>
					</table>
					
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