<?php
include "dbcon.php";

if(isset($_POST['postquiz-btn'])){
    $quizcontent = $_POST['quiz-questionn'];
    $answer = $_POST['answer'];
    $wrong_answer1 = $_POST['choice2n'];
    $wrong_answer2 = $_POST['choice3n'];
    $wrong_answer3 = $_POST['choice4n'];

    $insert = "INSERT INTO quiz(`quizcontent`, `answer`, `wrong_answer1`, `wrong_answer2`, `wrong_answer3`)
    VALUES('$quizcontent', '$answer', '$wrong_answer1', '$wrong_answer2', '$wrong_answer3')";

    if ($conn->query($insert) === TRUE) {
        $message = "Quiz Posted!";
        header('location: adminpage.php?notif='.$message);
    } else {
        $message = "Error: " . $conn->error;
        header('location: adminpage.php?notif='.$message);
    }
}
?>
