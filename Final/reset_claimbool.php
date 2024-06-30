<?php
session_start();

if (!isset($_SESSION['login'])) {
    // Redirect user if not logged in
    header('location:login-page.php');
    exit();
}

include "dbcon.php";

$login_id = $_SESSION['login_id'];

// Update claimbool to TRUE in the database
$sql = "UPDATE user SET claimbool = TRUE WHERE tupv_id = '$login_id'";

if ($conn->query($sql) === TRUE) {
    // Return success response
    http_response_code(200);
    echo json_encode(array("message" => "claimbool reset to TRUE"));
} else {
    // Return error response
    http_response_code(500);
    echo json_encode(array("message" => "Error resetting claimbool: " . $conn->error));
}

$conn->close();
?>
