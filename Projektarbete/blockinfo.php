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
				<a href="Index.php"><img src="legobit.png" alt="Picture couldnt be laoded"></a>
			</li>
			<li class="text"><a href="help.php">Help</a></li>


		</ul>
	</div>

<?php	
// Koppla	upp	mot	databasen
	try {
		$connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
	} catch (Exception $e) {
		// Catch error messages if connection failed
		$error = $e->getMessage();
		echo $error;
	}
	if (!$connection) {
		die('MySQL connection error');
	}
	$limit = 10;
	$offset = $_GET['offset'];

			$colorID = $_GET['colorID'];
			$itemID = $_GET['itemID'];
			$partname = $_GET['partname'];
			
		$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
		
		$query ="SELECT DISTINCT parts.PartID, inventory.ItemID, sets.SetID, inventory.ColorID, sets.setname, parts.partname, sets.year
			FROM parts, inventory, colors, sets, images
			WHERE inventory.ColorID = '$colorID' AND inventory.ItemID = '$itemID' AND parts.Partname = '$partname'
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID 
			AND colors.ColorID=images.ColorID 
            AND inventory.ItemID = images.ItemID
			AND sets.SetID=inventory.SetID LIMIT $limit OFFSET $offset";

			$imagequery = "SELECT * FROM images WHERE ItemID='$itemID' AND ColorID='$colorID'";
			$imageresult = mysqli_query($connection, $imagequery);
			$imageData = mysqli_fetch_array($imageresult);

			if ($imageData['has_gif']) {
				$filename = "P/" . $colorID . "/" . $itemID . ".gif";
			} else if ($imageData['has_jpg']) {
				$filename = "P/$colorID/$itemID.jpg";
			}
			$route = $link . $filename;
			echo ("<div id='partpicture'><img src=\"$route\" alt=\"image not found\"></div>");
			echo ("<div id='partname'> $partname </div>");

	
	

	$result = mysqli_query($connection, $query);
	
		echo ("<table>\n<tr>");
		echo ("<th>Picture</th>");
		echo ("<th>Setname</th>");
		echo ("<th>SetID</th>");
		echo ("<th>Year </th>");
		while ($row = mysqli_fetch_array($result)){
			$imgID =  $row['SetID'];
			$imgColor = $row['ColorID'];
			$setName = $row['setname'];
			$partname = $row['partname'];
			$setID = $row['SetID'];
			$year = $row['year'];
			$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
			
			
			$imagequery = "SELECT * FROM images WHERE itemID='$imgID'";
			$imageresult = mysqli_query($connection, $imagequery);
			$imageData = mysqli_fetch_array($imageresult);
			
			
			if ($imageData['has_gif']) {
				$filename = "S/" . $imgID . ".gif";
			} else if ($imageData['has_jpg']) {
				$filename = "S/$imgID.jpg";
			}
			
			$route = $link . $filename;
			//	$blockquery = "Select * FROM "
			echo ("<tr>");
			echo ("<td><img src=\"$route\" alt=\"image not found\"></td>");
			echo ("<td> $setName </td>");
			echo ("<td> $setID </td>");
			echo ("<td> $year </td>");
			
			
			
			echo ("</tr>\n");
		}
	echo "<tr>\n";
	
	echo ("</table>");
	
		$pagequery ="SELECT DISTINCT parts.PartID, inventory.ItemID, sets.SetID, inventory.ColorID, sets.setname, parts.partname, sets.year
			FROM parts, inventory, colors, sets, images
			WHERE inventory.ColorID = '$colorID' AND inventory.ItemID = '$itemID' AND parts.Partname = '$partname'
			AND inventory.ItemTypeID='P' 
			AND inventory.ItemID = parts.PartID 
			AND colors.ColorID=images.ColorID 
            AND inventory.ItemID = images.ItemID
			AND sets.SetID=inventory.SetID";
			
			$fullresult = mysqli_query($connection, $pagequery);
			$numrows = mysqli_num_rows($fullresult);
			
			
	if ($numrows > 10) {
    echo "<div class='pages'>";
		$maxresult = $offset + 10;
	
		// Will put offset = firstpage i.e offset = 0
		$firstpage = 0;
		echo('<form action="blockinfo.php" method="GET">');
		echo('<input type="hidden" class="offset" name="itemID" value="' . $itemID. '">');
		echo('<input type="hidden" class="offset" name="colorID" value="' . $colorID. '">');
		echo('<input type="hidden" class="offset" name="partname" value="' . $partname. '">');
		echo('<input type="hidden"	class="offset" name="offset" value="' . $firstpage . '">');
		echo('<button style="padding:5px;" type="submit">First page</button>');
		echo('</form>');
		
		// If offset is bigger than 0, nextpage button will show. Adds +10 to offset (inside loop)
		
		if($offset >0){
			$previouspage = ($offset-10);
			for($page = -1; $page < $previouspage; $offset-10){
			
			$previouspage = ($offset-10);
			echo('<form action="blockinfo.php" method="GET">');
			echo('<input type="hidden" class="offset" name="itemID" value="' . $itemID. '">');
			echo('<input type="hidden" class="offset" name="colorID" value="' . $colorID. '">');
			echo('<input type="hidden" class="offset" name="partname" value="' . $partname. '">');
			echo('<input type="hidden"	class="offset" name="offset" value="' . $previouspage . '">');
			echo('<button style="padding:5px;" type="submit">Previous page</button>');
			echo('</form>');
			$page = $previouspage;
			
		}
		}
		
		//If offset is less than numberofpages*10, previous button will show. Subtracts 10 from offset (inside loop)
		if($maxresult <= $numrows-10){
		$nextpage = ($offset + 10);
		for($page = 0; $page < $nextpage; $offset +10){
			
			$nextpage = ($offset + 10);
			echo('<form action="blockinfo.php" method="GET">');
			echo('<input type="hidden" class="offset" name="itemID" value="' . $itemID. '">');
			echo('<input type="hidden" class="offset" name="colorID" value="' . $colorID. '">');
			echo('<input type="hidden" class="offset" name="partname" value="' . $partname. '">');
			echo('<input type="hidden"	class="offset" name="offset" value="' . $nextpage . '">');
			echo('<button style="padding:5px;" type="submit">Next page</button>');
			echo('</form>');
			$page = $nextpage;
		}
		}
	
		//Last page button
		$lastpage = ($numrows - 10);
		
		echo('<form action="blockinfo.php" method="GET">');
		echo('<input type="hidden" class="offset" name="itemID" value="' . $itemID. '">');
		echo('<input type="hidden" class="offset" name="colorID" value="' . $colorID. '">');
		echo('<input type="hidden" class="offset" name="partname" value="' . $partname. '">');
		echo('<input type="hidden"	class="offset" name="offset" value="' . $lastpage . '">');
		echo('<button style="padding:5px;" type="submit">Last page</button>');
		echo('</form>');
		
		echo("</div>");
		
		
	}

















?>
</body>
</html>