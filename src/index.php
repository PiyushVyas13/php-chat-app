<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
    crossorigin="anonymous"
  />
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"
  ></script>
  <link href="./style.css" rel="stylesheet"/>
<?php
include_once "auth.php";
include "header.php";
include_once "database.php";


// Show the last three chat rooms
$chatRooms = get_chat_rooms(3);
echo "<div class='divIndex container'>";
echo "<h2>Last 3 chat rooms</h2>";
echo "<ol class='list-group'>";
foreach ($chatRooms as $chatRoom) {
    echo "<li class='list-group-item list-group-item-action listStyle'><a class='nav-link ' href='chat.php?id=" . $chatRoom['id'] . "'>" . $chatRoom['name'] . "</a></li>";
}
echo "</ol>";
echo "</div>";

include_once "footer.php";
