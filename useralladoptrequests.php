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
			$results = $db->query("SELECT art.adoptionID, aml.animalID, art.userID, aml.name, aml.type, art.approved FROM adoption_request art JOIN animal aml ON art.animalID = aml.animalID WHERE userID = '$user'");
				$count = $results->rowCount();
				
				if ($count == 0) {
					echo "You have no Adoption Requests";
				} else {
					echo "
					My Adoption Requests:
					<table>
						<tr>
							<th>Animal Name</th>
							<th>Animal Type</th>
							<th>Adoption Status</th>
							<th>More</th>
						</tr>
					";
					foreach($results as $row) {
						echo "
							<tr>
								<td>$row[name]</td>
								<td>$row[type]</td>
						";
						
						if ($row['approved'] == 1) {
							echo "
								<td>Approved</td>
								<td><a href='animal.php?animalID=$row[animalID]'>Animal Details</a></td>
							";
						} elseif ($row['approved'] ==  NULL) {
							echo "
								<td>Pending</td>
								<td><a href='animal.php?animalID=$row[animalID]'>Animal Details</a> | <a href='cancelreq.php?adoptID=$row[adoptionID]'>Cancel</a></td>
							";
						} elseif ($row['approved'] ==  2) {
							echo "
								<td>User Cancelled</td>
								<td><a href='animal.php?animalID=$row[animalID]'>Animal Details</a></td>
							";
						} else {
							echo "
								<td>Declined</td>
								<td><a href='animal.php?animalID=$row[animalID]'>Animal Details</a></td>
							";
						}
						echo "
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