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
	

$colorID = $_GET['colorID'];
$itemID = $_GET['itemID'];
$partname = $_GET['partname'];
	$link = "http://www.itn.liu.se/~stegu76/img.bricklink.com/";
		$query ="SELECT DISTINCT parts.PartID, inventory.ItemID, sets.SetID, inventory.ColorID, sets.setname, parts.partname, sets.year
			FROM parts, inventory, colors, sets, images
			WHERE inventory.ColorID = '$colorID' AND inventory.ItemID = '$itemID' AND parts.partname = '$partname'
			AND inventory.ItemTypeID='P' 
			AND inventory.ItemID = parts.PartID 
			AND colors.ColorID=images.ColorID 
            AND inventory.ItemID = images.ItemID
			AND sets.SetID=inventory.SetID LIMIT 10";

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
	$numrows = mysqli_num_rows($result);
	$numberOfPages = ceil($numrows / $limit);
		
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
	






?>
</body>
</html>
