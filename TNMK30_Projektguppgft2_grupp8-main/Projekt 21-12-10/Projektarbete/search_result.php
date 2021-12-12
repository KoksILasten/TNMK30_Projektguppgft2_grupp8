<!DOCTYPE html>
<?php include"meny.txt"; ?>
<link href="search_result_style.css" media="screen" rel="stylesheet" type="text/css" />
<script src="javaprojekt.js"></script>


	<div class="content_table">
		<?php
		// Koppla	upp	mot	databasen
			$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
			if (!$connection) {
			die('MySQL connection error');
			}
			
			$search = $_GET['userinput'];
			
			$query = "SELECT parts.PartID, inventory.ItemID, sets.SetID, inventory.ColorID, colors.Colorname, sets.setname, sets.year, parts.partname FROM parts, inventory, sets, colors 
			WHERE (parts.PartID LIKE '%$search%' OR parts.partname LIKE '%$search%') AND  inventory.ItemID = parts.PartID AND sets.SetID = inventory.SetID AND sets.SetID = inventory.ColorID AND 
			colors.ColorID=inventory.ColorID AND inventory.ItemTypeID='P'";
			//	Ställ	frågan		
			$result = mysqli_query($connection, $query);
			
			
			
			print("<table>\n<tr>");
			print("<th>Setname</th>");
			print("<th>Color</th>");
			print("<th>Partname</th>");
			print("<th>Picture</th>");
			print("<th>Year</th>");
			while($row = mysqli_fetch_array($result)){
				$imgID =  $row['ItemID'];
				$imgColor = $row['ColorID'];
				$setName = $row['setname'];
				$color = $row['Colorname'];
				$partname = $row['partname'];
				$year = $row['year'];
				$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
				
				$imagequery = "SELECT * FROM images WHERE ItemID='$imgID' AND ColorID='$imgColor'";
				$imageresult = mysqli_query($connection, $imagequery);
				$imageData = mysqli_fetch_array($imageresult);
				
				if($imageData['has_gif']){
						$filename = "P/" . $imgColor . "/" . $imgID . ".gif";
					}else if($imageData['has_jpg']){
						$filename = "P/$imgColor/$imgID.jpg";
					}
					
				
				$route = $link.$filename;
			
					
				print("<tr>");
				print("<td> $setName </td>");
				print("<td> $color </td>");
				print("<td> $partname </td>");
				print("<td><img src=\"$route\" alt=\"image not found\"></td>");
				print("<td> $year </td>");
				print ("</tr>\n");
			
			}
			print "<tr>\n";
			mysqli_close($connection);
			print("</table>");
			?>
	 </div>
</body>
</html>