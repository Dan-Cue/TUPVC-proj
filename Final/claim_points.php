<?php
session_start();
include "dbcon.php";

if (!isset($_SESSION['login_id'])) {
    echo json_encode(array("success" => false, "message" => "User not logged in."));
    exit();
}

$login_id = $_SESSION['login_id'];

// Check if the claimbool is TRUE
$sql_check_claimbool = "SELECT claimbool FROM user WHERE tupv_id = '$login_id'";
$result_check_claimbool = $conn->query($sql_check_claimbool);

if ($result_check_claimbool->num_rows > 0) {
    $row = $result_check_claimbool->fetch_assoc();
    $claimbool = $row["claimbool"];
    if ($claimbool == 'FALSE') {
        echo json_encode(array("success" => false, "message" => "You have already claimed your daily points."));
        exit();
    }
} else {
    echo json_encode(array("success" => false, "message" => "User data not found."));
    exit();
}

// Increment points
$increment = 5;
$sql_update_points = "UPDATE user SET points = points + $increment WHERE tupv_id = '$login_id'";
$sql_update_claimbool = "UPDATE user SET claimbool = FALSE WHERE tupv_id = '$login_id'";

if ($conn->query($sql_update_points) === TRUE && $conn->query($sql_update_claimbool) === TRUE) {
    echo json_encode(array("success" => true, "message" => "Points claimed successfully!"));
} else {
    echo json_encode(array("success" => false, "message" => "Error updating points: " . $conn->error));
}

$conn->close();
?>
