<?php
session_start();
include "dbcon.php";

if (!isset($_SESSION['login'])) {
    header('location: login-page.php');
    exit();
}

$login_id = $_SESSION['login_id'];

// Check if the selected answer matches the correct answer
$selectedAnswer = $_POST['selectedAnswer'];
$correctAnswer = $_POST['correctAnswer'];

if ($selectedAnswer === $correctAnswer) {
    // Add 5 points to the user's points in the database
    $sql_update_points = "UPDATE user SET points = points + 5 WHERE tupv_id = ?";
    $stmt = $conn->prepare($sql_update_points);
    $stmt->bind_param("s", $login_id);

    if ($stmt->execute()) {
        echo "Points added successfully!";
    } else {
        error_log("Error adding points: " . $stmt->error);
        echo "Error adding points. Please try again later.";
    }

    $stmt->close();
} else {
    echo "Incorrect answer.";
}
?>
