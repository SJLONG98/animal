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
			$results = $db->query("SELECT a.animalID, a.name, a.type, a.photo_link FROM animal a JOIN owns o ON a.animalID = o.animalID WHERE o.userID = '$user'");
			$count = $results->rowCount();
			echo "
				<div class=main>
				";
				if ($count == 0) {
					echo "
						You currently have no adopted animals.
					";
				} else {
					echo "
					Your Adopted Animals:
					<table>
						<tr>
							<th>Name</th>
							<th>Type</th>
							<th>Photo</th>
							<th>More</th>
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
									<td><a href='animal.php?animalID=$row[animalID]'>More Details</a></td>
								</tr>
							";
						};
						echo "
					</table>";
				}
				echo "
				<br><br>
				
				</div>
			";
		} else {
			header('Location: login.php');
			exit();
		}
	?>
	
  </body>
  
</html>