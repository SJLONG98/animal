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
			if ($_SESSION["staff"] == "true") {
				include('mainstyle.php');
				
				require_once('dbconnect.php');
				echo "
				    <div class=main>
					";
				$results = $db->query("SELECT art.adoptionID, art.userID, aml.name, aml.type FROM adoption_request art JOIN animal aml ON art.animalID = aml.animalID WHERE approved IS NULL");
					$count = $results->rowCount();
					
					if ($count == 0) {
						echo "No Pending Adoption Requests";
					} else {
						echo "
						Pending Adoption Requests:
						<table>
							<tr>
								<th>User</th>
								<th>Animal Name</th>
								<th>Animal Type</th>
								<th>Action</th>
							</tr>
						";
						foreach($results as $row) {
							echo "
								<tr>
									<td>$row[userID]</td>
									<td>$row[name]</td>
									<td>$row[type]</td>
									<td><a href='approvereq.php?adoptID=$row[adoptionID]'>Approve</a> | <a href='declinereq.php?adoptID=$row[adoptionID]'>Decline</a></td>
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