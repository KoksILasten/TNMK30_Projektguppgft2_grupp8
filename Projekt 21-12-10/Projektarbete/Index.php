<?php include "meny.txt"; ?>



<div class="searchbar">
    <form action="search_result.php" method="GET">
        <input type="search" id="Searchinput" name="userinput" placeholder="Search for your parts here..." required>
        <button class="search" id="searchtext" type="submit"></button>
    </form>
    <div class="help">
        <button class="accordion" onclick="drop()">How to search</button>

        <div id="myPanel" class="panel">
            <p>Step 1: Click on the search bar.</p>
			<p>Step 2: Type in what you want to search for.</p>
        </div>

    </div>
</div>


<div id="bg-img">
    <div class="blur-size"></div>

</div>

</body>

</html>