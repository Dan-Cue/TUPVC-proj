<?php
session_start();

include "dbcon.php";

if (isset($_SESSION['login_id'])) {
    $login_id = $_SESSION['login_id'];
    $sql = "SELECT claimbool FROM user WHERE tupv_id = '$login_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $claimbool = (bool)$row["claimbool"]; // Convert claimbool to boolean
        echo json_encode(["claimbool" => $claimbool]);
    } else {
        echo json_encode(["claimbool" => false]);
    }
} else {
    echo json_encode(["claimbool" => false]);
}
?>