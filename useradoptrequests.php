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
					width:800px;
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
				
			</style>
		";
		
		if (isset($_SESSION["username"])) {
			include('mainstyle.php');
			$user = $_SESSION['username'];
			require_once('dbconnect.php');
			echo "
				<div class=main>
				";
			$results = $db->query("SELECT art.adoptionID, art.userID, aml.name, aml.type, aml.photo_link FROM adoption_request art JOIN animal aml ON art.animalID = aml.animalID WHERE approved IS NULL AND art.userID = '$user'");
				$count = $results->rowCount();
				
				if ($count == 0) {
					echo "No Pending Adoption Requests";
				} else {
					echo "
					Pending Adoption Requests:
					<table>
						<tr>
							<th>Animal Name</th>
							<th>Animal Type</th>
							<th>Photo</th>
							<th>Action</th>
						</tr>
					";
					foreach($results as $row) {
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
								<td><a href='cancelreq.php?adoptID=$row[adoptionID]'>Cancel</a></td>
							</tr>
						";
					};
					echo "
					</table>
					";
				}
			echo "
				</div>
			";
		} else {
			header('Location: login.php');
			exit();
		}
	?>
	
  </body>
  
</html>