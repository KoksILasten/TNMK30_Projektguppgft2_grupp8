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
	$offset = $_GET['offset'];
	
	//Har tagit bort färgnamn. Lägg till inventory.ColorID = colors.ColorID och colors.name för att få namn
	
	//Will check if user search starts with a number-
		if(is_numeric($search[0])){
		$fullquery ="SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname
			FROM parts, inventory, colors, images
			WHERE parts.PartID LIKE '$search' 
			AND inventory.ItemTypeID='P' 
			AND inventory.ItemID = parts.PartID 
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			$fullresult = mysqli_query($connection, $fullquery);
			$numrows = mysqli_num_rows($fullresult);
			if($numrows === 0){
			$fullquery ="SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname
			FROM parts, inventory, colors, images
			WHERE parts.PartID LIKE '%$search%' 
			AND inventory.ItemTypeID='P' 
			AND inventory.ItemID = parts.PartID 
			
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			}
			
			
	}else{
			$fullquery = "SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname
			FROM parts, inventory, colors, images
			WHERE parts.Partname LIKE '$search' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID 
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			$fullresult = mysqli_query($connection, $fullquery);
			$numrows = mysqli_num_rows($fullresult);
			if($numrows === 0){
			$fullquery = "SELECT DISTINCT parts.PartID, inventory.ItemID, inventory.ColorID, parts.partname
			FROM parts, inventory, colors, images
			WHERE parts.Partname LIKE '%$search%' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID
			AND colors.ColorID=images.ColorID LIMIT $limit OFFSET $offset";
			}
	}
	//To get full results with the number of rows aka 
	if(is_numeric($search[0])){
			$pagequery	= "SELECT DISTINCT parts.PartID, inventory.ColorID, parts.Partname
			FROM parts, inventory
			WHERE parts.partID LIKE '$search' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID";
			
			$pageresult = mysqli_query($connection, $pagequery);
			$pagenumrows = mysqli_num_rows($pageresult);
			
			if($pagenumrows === 0){
				$pagequery	= "SELECT DISTINCT parts.PartID, inventory.ColorID, parts.Partname
			FROM parts, inventory
			WHERE parts.partID LIKE '%$search%' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID 
			";
			}
	}else{
		$pagequery	= "SELECT DISTINCT parts.PartID, inventory.ColorID, parts.partname
			FROM parts, inventory
			WHERE parts.Partname LIKE '$search' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID";

			$pageresult = mysqli_query($connection, $pagequery);
			$pagenumrows = mysqli_num_rows($pageresult);
			if($pagenumrows === 0){
				
				$pagequery	= "SELECT DISTINCT parts.PartID, inventory.ColorID
			FROM parts, inventory
			WHERE parts.partname LIKE '%$search%' 
			AND inventory.ItemTypeID='P'
			AND inventory.ItemID = parts.PartID";
			
			}
	}
	
	
	$fullresult = mysqli_query($connection, $pagequery);
	$result = mysqli_query($connection, $fullquery);
	$numrows = mysqli_num_rows($fullresult);
	$numberOfPages = ceil($numrows / $limit);
	
	
	//If there is no rows, will not show table.
	if ($numrows === 0) {
		echo ("<h1>No result for your search  '$search' </h1>");
		echo ("<h1>Try again!</h1>");
	} else {
		echo ("<h1>Your search '$search' gave $numrows results</h1>");
		echo ("<table>\n<tr>");
		echo ("<th>Picture</th>");
		echo ("<th>Partname</th>");
		
		echo ("<th>Link</th>");
		while ($row = mysqli_fetch_array($result)) {
			$imgID =  $row['ItemID'];
			$imgColor = $row['ColorID'];
			$setName = $row['setname'];
			$partname = $row['partname'];
			
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
			
			echo ("<td> <a href='blockinfo.php?itemID=$imgID&colorID=$imgColor&partname=$partname&offset=0'>Look for sets</a></td>");
			echo ("</tr>\n");
		}
	}
	echo ("<tr>\n");
	
	echo ("</table>");
	
	// Calculates which search results to show, based on current page
	
	
	//If there is more than 1 page, next/previous/last/first button will show.
	if ($numberOfPages > 1) {
    echo "<div class='pages'>";
	
	
		// Will put offset = firstpage i.e offset = 0
		$firstpage = 0;
		echo('<form action="search_result.php" method="GET">');
		echo('<input type="hidden" class="offset" name="userinput" value="' . $search . '">');
		echo('<input type="hidden"	class="offset" name="offset" value="' . $firstpage . '">');
		echo('<button style="padding:5px;" type="submit">First page</button>');
		echo('</form>');
		
		// If offset is bigger than 0, nextpage button will show. Adds +10 to offset (inside loop)
		$maxresult = $offset + 10;
		if($offset >0){
			$previouspage = ($offset-10);
			for($page = -1; $page < $previouspage; $offset-10){
			
			$previouspage = ($offset-10);
			echo('<form action="search_result.php" method="GET">');
			echo('<input type="hidden" class="offset" name="userinput" value="' . $search . '">');
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
			echo('<form action="search_result.php" method="GET">');
			echo('<input type="hidden" class="offset" name="userinput" value="' . $search . '">');
			echo('<input type="hidden"	class="offset" name="offset" value="' . $nextpage . '">');
			echo('<button style="padding:5px;" type="submit">Next page</button>');
			echo('</form>');
			$page = $nextpage;
		}
		}
	
		//Last page button
		$lastpage = ($numrows - 10);
		
		echo('<form action="search_result.php" method="GET">');
		echo('<input type="hidden" class="offset" name="userinput" value="' . $search . '">');
		echo('<input type="hidden"	class="offset" name="offset" value="' . $lastpage . '">');
		echo('<button style="padding:5px;" type="submit">Last page</button>');
		echo('</form>');
		echo ("</div>");
		
	}
	mysqli_close($connection);
	?>
		
</body>

</html>