<?php
include "dbcon.php";

if(isset($_POST['addreward-btn'])){
    $rewardname = $_POST['rewardname'];
    $price = $_POST['price'];


    $insert = "INSERT INTO rewards(`reward_name`, `cost`)
    VALUES('$rewardname', '$price')";

    if ($conn->query($insert) === TRUE) {
        $message = "Successful!";
        header('location: adminpage.php?notif='.$message);
    } else {
        $message = "Error: " . $conn->error;
        header('location: adminpage.php?notif='.$message);
    }
}
?>