<?php include "meny.txt"; ?>



<div class="searchbar">
    <form action="search_result.php" method="GET">
        <input type="search" id="Searchinput" name="userinput" placeholder="Search for your parts here..." required>
        <button class="search" id="searchtext" type="submit"></button>
    </form>
    <div class="help">
        <button class="accordion" onclick="drop()">How to search</button>

        <div id="myPanel" class="panel">
            <ol>
                <li>Click with the cursor on the search bar.</li>
				<br></br>
                <li>Enter a valid brickname or brick ID</li>
                <ul>
                    <p>Example:
                    <p>
                        <li>Brick 2 x 2 ([item][size])</li>
                        <li>3004 ([itemID])</li>
                </ul>

            </ol>
        </div>

    </div>
</div>


<div id="bg-img">
    <div class="blur-size"></div>

</div>

</body>

</html>
