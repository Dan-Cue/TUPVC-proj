<?php
    include "dbcon.php";

    if($_SERVER["REQUEST_METHOD"]== "POST"){
        if(isset($_POST['login'])){
            $login_id = $_POST['login_id'];
            $password = $_POST['password'];

            $login = "SELECT * FROM user WHERE tupv_id = '$login_id'";
            $result = $conn->query($login);

            if($result->num_rows > 0){
                $row = $result->fetch_assoc();

                if($row['password'] == $password){
                    session_start();

                    $_SESSION['login_id'] = $row['tupv_id'];
                    $_SESSION['password'] = $row['password'];
                    $_SESSION['login'] = true;

                    header('location: home.php');
                    
                } else {
                    $message = "Incorrect password";
                    header('location: login-page.php?notif='.$message);
                }
            } else {
                $message = "Username not Found!";
                header('location: login-page.php?notif='.$message);
            }
        }
    }
?>