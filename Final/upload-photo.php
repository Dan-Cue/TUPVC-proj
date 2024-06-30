<?php
// Check if tupv_id is numeric and greater than zero
if (isset($_POST['tupv_id']) && is_numeric($_POST['tupv_id']) && $_POST['tupv_id'] > 0 && isset($_FILES['newPicture']) && !empty($_FILES['newPicture'])) {
    // Database connection
    $server = "localhost";
    $user = "root";
    $pass = "";
    $database = "tupvconnectdb";

    $conn = new mysqli($server, $user, $pass, $database);
    if($conn->connect_error){
        die("Connection Failed" . $conn->connect_error);
    }
    // Prepare data for insertion
    $userId = $_POST['tupv_id'];
    $newPicture = file_get_contents($_FILES['newPicture']['tmp_name']); // Read the file
    $newPicture = $conn->real_escape_string($newPicture); // Escape special characters

    // Update the database
    $sql = "UPDATE user SET picture = '$newPicture' WHERE tupv_id = $userId";

    if ($conn->query($sql) === TRUE) {
        echo "Photo updated successfully";
    } else {
        echo "Error updating photo: " . $conn->error;
    }

    // Close connection
    $conn->close();
} else {
    echo "User ID or picture not provided";
}

?>
