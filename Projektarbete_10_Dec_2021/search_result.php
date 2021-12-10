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
                <a href="Index.php"><img src="legobit.png" alt="Picture couldn´t be laoded" style="width: 3vw;height:3vw;"></a>
            </li>
            <li class="text"><a href="help.php">Help</a></li>


        </ul>
    </div>

<?php
	$connection = mysqli_connect("mysql.itn.liu.se","lego","","lego");
	if (!$connection) {
	die('MySQL connection error');
	}
	
	
	
	$query = "SELECT inventory.Quantity, colors.Colorname, parts.Partname, inventory.ItemID, inventory.ColorID FROM inventory, colors, parts 
		WHERE inventory.SetID='375-2' AND colors.ColorID=inventory.ColorID AND parts.PartID=inventory.ItemID AND inventory.ItemTypeID='P'";
		
	$result = mysqli_query($connection, $query);
	

	
	print("<table>\n<tr>");
	print("<th>Quantity</th>");
	print("<th>Color</th>");
	print("<th>Parts</th>");
	print("<th>Picture</th>");
	
	while($row = mysqli_fetch_array($result)){
		$imgID =  $row['ItemID'];
		$imgColor = $row['ColorID'];
		
		$imagequery = "SELECT * FROM images WHERE ItemID='$imgID' AND ColorID='$imgColor'";
		$imageresult = mysqli_query($connection, $imagequery);
		$imageData = mysqli_fetch_array($imageresult);
		
		$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
		$quantity = $row['Quantity'];
		$color = $row['Colorname'];
		$partname = $row['Partname'];
	
		$imgColor = $row['ColorID'];
		
			if($imageData['has_gif']){
				$filename = "P/" . $imgColor . "/" . $imgID . ".gif";
			}else if($imageData['has_jpg']){
				$filename = "P/$imgColor/$imgID.jpg";
			}
			else{
				print("no Image");
			}
		
			$route = $link.$filename;
			
		print("<tr>");
		print("<td> $quantity </td>");
		print("<td> $color </td>");
		print("<td> $partname </td>");
		print("<td> $filename </td>");
		print("<td><img src=\"$route\" alt=\"image not found\"></td>");
		print ("</tr>\n");
	}
	print "<tr>\n";
	mysqli_close($connection);
	print("</table>");
	?>
	http://www.itn.liu.se/~stegu76/img.bricklink.com/P/3/.gif
	 
	</body>
	</html>