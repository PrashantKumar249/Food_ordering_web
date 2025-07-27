<?php
include("inc/db.php");

if (isset($_POST['query'])) {
    $query = mysqli_real_escape_string($conn, $_POST['query']);
    $result = mysqli_query($conn, "SELECT name FROM menu_items WHERE name LIKE '%$query%' LIMIT 5");

    if(mysqli_num_rows($result) > 0){
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='suggestion-item px-4 py-2 hover:bg-gray-100 cursor-pointer'>"
                 . htmlspecialchars($row['name']) .
                 "</div>";
        }
    } else {
        echo "<div class='px-4 py-2 text-gray-500'>No suggestions found</div>";
    }
}
?>
