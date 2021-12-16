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

			<li class="text"><a href="aboutsus.php"> About Us</a></li>
			<li id="lego">
				<a href="Index.php"><img src="legobit.png" alt="Picture couldnt be laoded"></a>
			</li>
			<li class="text"><a href="help.php">Help</a></li>


		</ul>
	</div>

	<div class="searchbar">
		<form action="search_result.php" method="GET">
			<input type="search" id="Searchinput" name="userinput" placeholder="Search for your parts here..." required>
			<input type="hidden" id="offset" name="offset" value="0">
			<button class="search" id="searchtext" type="submit"></button>
		</form>

	</div>


	<?php
	// Koppla	upp	mot	databasen
	$connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
	
	if (!$connection) {
		die('MySQL connection error');
	}
	if (!isset($_GET['userinput'])) {
		header("Location: Index.php"); //sends you back to index in false
		die();
	}
	$search = $_GET['userinput'];


	$limit = 10;
	$offset = $_GET['offset']; //+ blir fel eller något ://
	
		if(is_numeric($search[0])){
		$fullquery ="SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname, colors.Colorname
			FROM parts, inventory, colors, images
			WHERE parts.PartID LIKE '$search' 
			AND inventory.ItemTypeID='P' 
			AND inventory.ItemID = parts.PartID 
			AND inventory.ColorID = colors.ColorID
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			$fullresult = mysqli_query($connection, $fullquery);
			$numrows = mysqli_num_rows($fullresult);
			if($numrows === 0){
			$fullquery ="SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname, colors.Colorname
			FROM parts, inventory, colors, images
			WHERE parts.PartID LIKE '%$search%' 
			AND inventory.ItemTypeID='P' 
			AND inventory.ItemID = parts.PartID 
			AND inventory.ColorID = colors.ColorID
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			}
			
	}else{
	    $fullquery = "SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname, colors.Colorname
			FROM parts, inventory, colors, images
			WHERE parts.Partname LIKE '$search' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID 
			AND inventory.ColorID = colors.ColorID
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			$fullresult = mysqli_query($connection, $fullquery);
			$numrows = mysqli_num_rows($fullresult);
			if($numrows === 0){
			$fullquery = "SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname, colors.Colorname
			FROM parts, inventory, colors, images
			WHERE parts.Partname LIKE '%$search%' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID
			AND inventory.ColorID = colors.ColorID			
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			}
	}
	
	$pagequery	= "SELECT DISTINCT parts.PartID, parts.Partname FROM parts WHERE (parts.Partname LIKE '%$search%' OR parts.PartID LIKE '%$search%')";
	
	$fullresult = mysqli_query($connection, $pagequery);
	$result = mysqli_query($connection, $fullquery);
	$numrows = mysqli_num_rows($fullresult);
	$numberOfPages = ceil($numrows / $limit);

	if ($numrows === 0) {
		echo ("<h1>No result for your search  '$search' </h1>");
		echo ("<h1>Try again!</h1>");
	} else {
		echo ("<h1>Your search '$search' gave $numrows results</h1>");
		echo ("<table>\n<tr>");
		echo ("<th>Picture</th>");
		echo ("<th>Partname</th>");
		echo ("<th>Color</th>");
		echo ("<th>Link</th>");
		while ($row = mysqli_fetch_array($result)) {
			$imgID =  $row['ItemID'];
			$imgColor = $row['ColorID'];
			$setName = $row['setname'];
			$partname = $row['partname'];
			$colorname = $row['Colorname'];
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
			echo ("<tr>");
			echo ("<td><img src=\"$route\" alt=\"image not found\"></td>");
			echo ("<td> $partname </td>");
			echo ("<td> $colorname </td>");
			echo ("<td> <a href='blockinfo.php?itemID=$imgID&colorID=$imgColor&partname=$partname'>Look for sets</a></td>");
			echo ("</tr>\n");
		}
	}
	echo "<tr>\n";
	mysqli_close($connection);
	echo ("</table>");
	
	// Calculates which search results to show, based on current page
	if ($numberOfPages > 1) {
    echo "<div class='pages'>";
    for ($page = 1; $page <= $numberOfPages; $page++) {
	$offset = $page * $limit;

		echo('<form action="search_result.php" method="GET">');
		echo('<input type="hidden" id="offset" name="userinput" value="' . $search . '">');
		echo('<input type="hidden" id="offset" name="offset" value="' . $offset . '">');
		echo('<button style="padding:5px;" type="submit">' . $page . '</button>');
		echo('</form>');
	}
	echo "</div>";
		
	}
	?>

</body>

</html>
