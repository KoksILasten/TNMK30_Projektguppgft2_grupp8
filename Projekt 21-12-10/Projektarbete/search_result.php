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

	<div class="searchbar">
		<form action="search_result.php" method="GET">
			<input type="search" id="Searchinput" name="userinput" placeholder="Search for your parts here..." required>
			<button class="search" id="searchtext" type="submit"></button>
		</form>

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
	if (!isset($_GET['userinput'])) {
		header("Location: Index.php"); //sends you back to index in false
		die();
	}
	$search = $_GET['userinput'];

	$query = "SELECT parts.PartID, inventory.ItemID, sets.SetID, inventory.ColorID, colors.Colorname, sets.setname, sets.year, parts.partname FROM parts, inventory, sets, colors 
		WHERE (parts.PartID LIKE '%$search%' OR parts.partname LIKE '%$search%') AND  inventory.ItemID = parts.PartID AND sets.SetID = inventory.SetID AND sets.SetID = inventory.ColorID AND 
		colors.ColorID=inventory.ColorID AND inventory.ItemTypeID='P'";
	//	Ställ	frågan		
	$result = mysqli_query($connection, $query);
	$numrows = mysqli_num_rows($result);
	$numberOfPages = ceil($numrows / $limit);

	// Find which page the user is on
	if (!isset($_GET['page'])) {
		$page = 1;
	} else {
		$page = $_GET['page'];
	}

	// Calculates which search results to show, based on current page
	$thisPageFirstResult = ($page - 1) * $limit;

	if ($numrows === 0) {
		echo ("<h1>No result for your search  '$search' </h1>");
		echo ("<h1>Try again!</h1>");
	} else {
		echo ("<h1>Your search '$search' gave $numrows results</h1>");
		echo ("<table>\n<tr>");
		echo ("<th>Picture</th>");
		echo ("<th>Partname</th>");
		echo ("<th>Color</th>");
		echo ("<th>Year</th>");
		echo ("<th>Link</th>");
		while ($row = mysqli_fetch_array($result)) {
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

			if ($imageData['has_gif']) {
				$filename = "P/" . $imgColor . "/" . $imgID . ".gif";
			} else if ($imageData['has_jpg']) {
				$filename = "P/$imgColor/$imgID.jpg";
			}


			$route = $link . $filename;
			//	$blockquery = "Select * FROM "


			echo ("<tr>");
			echo ("<td><img src=\"$route\" alt=\"image not found\"></td>");
			echo ("<td> $partname </td>");
			echo ("<td> $color </td>");
			echo ("<td>$year </td>");
			echo ("<td><button type='submit'>Look at parts</button> </td>");
			echo ("</tr>\n");
		}
	}
	echo "<tr>\n";
	mysqli_close($connection);
	echo ("</table>");

	?>

</body>

</html>