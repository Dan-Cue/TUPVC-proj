<?php
include "dbcon.php";

if(isset($_POST['announce-btn'])){
    $announcecontent = $_POST['announcementcontent'];


    $insert = "INSERT INTO announcements(`announcement_content`)
    VALUES('$announcecontent')";

    if ($conn->query($insert) === TRUE) {
        $message = "Successful!";
        header('location: adminpage.php?notif='.$message);
    } else {
        $message = "Error: " . $conn->error;
        header('location: adminpage.php?notif='.$message);
    }
}
?>
