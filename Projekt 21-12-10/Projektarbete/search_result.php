<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lego Search</title>
    <link href="search_result_style.css" media="screen" rel="stylesheet" type="text/css" />
    <script src="javaprojekt.js"></script>
</head>

<body>
  <div class="headernavbar">
        <ul id="header">

            <li class="text"><a href="aboutus.php"> About Us</a></li>
            <li id="lego">
                <a href="Index.php"><img src="legobit.png" alt="Picture couldn´t be laoded" ></a>
            </li>
            <li class="text"><a href="help.php">Help</a></li>


        </ul>
    </div>
	
	 <div class="searchbar">
    <form action="search_result.php" method="GET">
        <input type="search" id="Searchinput" name="userinput"  placeholder="Search for your parts here..."  required  >
        <button class="search" id="searchtext" type="submit"></button>
    </form>
    
</div>

	
<?php
// Koppla	upp	mot	databasen
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
	if (!$connection) {
	die('MySQL connection error');
	}
	
	$search = $_GET['userinput'];
    
	if($search 
	$query = "SELECT parts.PartID, inventory.ItemID, sets.SetID, inventory.ColorID, colors.Colorname, sets.setname, sets.year, parts.partname FROM parts, inventory, sets, colors 
	WHERE (parts.PartID LIKE '%$search%' OR parts.partname LIKE '%$search%') AND  inventory.ItemID = parts.PartID AND sets.SetID = inventory.SetID AND sets.SetID = inventory.ColorID AND 
	colors.ColorID=inventory.ColorID AND inventory.ItemTypeID='P'";
	//	Ställ	frågan		
	$result = mysqli_query($connection, $query);
	$numrows = mysqli_num_rows($result);
	
	if ($numrows === 0){
		print("<h1>No result for your search  '$search' </h1>");
		print("<h1>Try again!</h1>");
		
	} 
else {
	print("<h1>Your search '$search' gave $numrows results</h1>");
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
	}
	print "<tr>\n";
	mysqli_close($connection);
	print("</table>");

	?>
	
	</body>
	</html>