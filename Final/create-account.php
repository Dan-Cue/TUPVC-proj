<?php
    include "dbcon.php";

    if(isset($_POST['create'])){
        $firstname = $_POST['first_name'];
        $lastname = $_POST['last_name'];
        $tupv_id = $_POST['ID_Number'];
        $course_section = $_POST['Course-Year&section'];
        $password = $_POST['new_password'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];

        $insert = "INSERT INTO user(`firstname`, `lastname`, `tupv_id`, `course_section`, `password`, `birthday`, `gender`)
        VALUES('$firstname', '$lastname', '$tupv_id', '$course_section', '$password', '$birthday', '$gender')";

        $result = $conn->query($insert);

        if($result == TRUE){
            $message = "Account Successfully created. You can now Log In.";
            header('location: login-page.php?notif='.$message);
        } else {
            $message = "Error Saving.";
            header('location: login-page.php?notif='.$message);
        }
    }

?>