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
				th.statown, td.statown {
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
		
		if (isset($_SESSION["username"])) {
			if ($_SESSION["staff"] == "true") {
				include('mainstyle.php');
				
				require_once('dbconnect.php');
				$results = $db->query("SELECT a.animalID, a.name, a.type, a.photo_link, a.available, o.userID FROM animal a JOIN owns o ON a.animalID = o.animalID ORDER BY a.animalID");
				$count = $results->rowCount();
				echo "
				    <div class=main>
					";
					if ($count == 0) {
						echo "
							The sanctuary currently has no animals
						";
					} else {
						echo "
						All Animals:
						<table>
							<tr>
								<th>Name</th>
								<th>Type</th>
								<th>Photo</th>
								<th class=statown>Status</th>
								<th class=statown>Owner</th>
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
										<td class=statown>Available</td>
									";
								} else {
									echo "
										<td class=statown>Not Available</td>
									";
								}
								
								echo "
										<td class=statown>$row[userID]</td>
										<td class=more><a href='animal.php?animalID=$row[animalID]'>More Details</a> | <a href='editanimal.php?animalID=$row[animalID]'>Edit</a></td>
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