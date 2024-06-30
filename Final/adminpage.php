<?php
session_start();

include "dbcon.php";


?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <link rel="stylesheet" type="text/css" href="adminstyle.css">
    <script>
        function showLayout(layoutId) {
            // Hide all layouts
            document.querySelectorAll('.layout').forEach(layout => {
                layout.style.display = 'none';
            });

            // Show the selected layout
            document.getElementById(layoutId).style.display = 'block';
        }

        function postAnnouncement() {
    var announcement = document.getElementById('announcement-text').value;
    if (announcement.trim() === '') {
        alert('Please enter your announcement.');
    } else {
        // Here you can send the announcement data to your backend or perform any other action
        alert('Announcement posted: ' + announcement);
        // You may also want to clear the textarea after posting
        document.getElementById('announcement-text').value = '';
    }
}

function postAnnouncement() {
    var announcement = document.getElementById('announcement-text').value;
    if (announcement.trim() === '') {
        alert('Please enter your announcement.');
    } else {
        // Here you can send the announcement data to your backend or perform any other action
        alert('Announcement posted: ' + announcement);
        // Display the posted announcement in the announcement-display area
        document.getElementById('announcement-display').innerHTML += `<div class="announcement-post">${announcement}</div>`;
        // You may also want to clear the textarea after posting
        document.getElementById('announcement-text').value = '';
    }
}

function postQuizQuestion() {
    var question = document.getElementById('quiz-question').value;
    var choices = [];
    document.querySelectorAll('.quizChoice').forEach(choice => {
        choices.push(choice.value);
    });
    if (question.trim() === '' || choices.some(choice => choice.trim() === '')) {
        alert('Please enter both the quiz question and all choices.');
    } else {
        // Here you can send the quiz question and choices data to your backend or perform any other action
        alert('Quiz question posted: ' + question + '\nChoices: ' + choices.join(', '));
        // You may also want to clear the text areas after posting
        document.getElementById('quiz-question').value = '';
        document.querySelectorAll('.quizChoice').forEach(choice => {
            choice.value = '';
        });
    }
}

function postRewards() {
    var rewards = [];
    document.querySelectorAll('.reward').forEach(reward => {
        rewards.push(reward.value);
    });
    if (rewards.some(reward => reward.trim() === '')) {
        alert('Please enter all rewards.');
    } else {
        // Here you can send the rewards data to your backend or perform any other action
        alert('Rewards posted: ' + rewards.join(', '));
        // You may also want to clear the text areas after posting
        document.querySelectorAll('.reward').forEach(reward => {
            reward.value = '';
        });
    }
}

function postEvent() {
    var eventDetails = document.getElementById('event-text').value;
    if (eventDetails.trim() === '') {
        alert('Please enter your event details.');
    } else {
        // Here you can send the event data to your backend or perform any other action
        alert('Event posted: ' + eventDetails);
        // You may also want to clear the textarea after posting
        document.getElementById('event-text').value = '';
    }
}

    </script>
</head>
<body>
    <header class="top-header">
        <img src="LOGO-TUPV-CONNECT.png" class="logo">
        <div class="search-bar">
            <img src="searchimg.png" class="search-icon">
            <input type="text" placeholder="Search">
        </div>
        <button class="logout-button">Logout</button>
    </header>
    
    <div class="container">
        <div class="column-box1">
            <div class="profile-box">
                <div class="profile-picture">
                </div>
                <div class="profile-info">
                    Admin
                </div>
            </div>
            <div class="button-container">
                <button class="announcement-button" onclick="showLayout('announcement-layout')">Announcements</button>
                <button class="quiz-button" onclick="showLayout('quiz-layout')">Quiz</button>
                <button class="rewards-button" onclick="showLayout('rewards-layout')">Rewards</button>
                <button class="event-button" onclick="showLayout('event-layout')">Events</button>
                <button class="users-button" onclick="showLayout('users-layout')">Users</button>
            </div>
        </div>
        <div class="column-box2">
            <div id="announcement-layout" class="layout" style="display: none;">
            <form action="createannouncement.php" method="POST">
                <input type="text" name="announcementcontent" id="announcement-text" class="announcementText" rows="4" cols="50" placeholder="Type your announcement here..." >
                <button class="postAnnouncement" type="submit" name="announce-btn">Post</button>
            </form>
            
            <div id="announcement-display" class="announcement-display">
                <?php
                $announcesql = "SELECT announcement_content FROM announcements WHERE announcement_id > 0 ORDER BY announcement_id DESC LIMIT 18";
                $result = $conn->query($announcesql);

                $announcements = array(); // Array to store announcements

                if ($result->num_rows > 0) {
                    // Loop through each row in the result set and store announcements in the array
                    while ($row = $result->fetch_assoc()) {
                        $announcements[] = $row["announcement_content"];
                    }

                    // Reverse the order of announcements
                    $announcements = array_reverse($announcements);

                    // Display the announcements
                    foreach ($announcements as $announcemsg) {
                        // Check if $announcemsg is not empty and not '#empty#'
                        if (!empty($announcemsg) && $announcemsg !== '#empty#') {
                            // Display "Announcement: " before each announcement message
                            echo '<div>📢Announcement📢: ' . $announcemsg . '</div>';
                        }
                    }
                } else {
                    echo "No announcements found.";
                }
                ?>
            </div>




            </div>
            
            <div id="quiz-layout" class="layout" style="display: none;">
            <form action="createquiz.php" method="POST">
                <input type="text" name="quiz-questionn" id="quiz-question" class="quizQuestion" rows="4" cols="50" placeholder="Type your quiz question here..."></textarea>
                <input type="text" name="answer" id="choice1" class="quizChoice"  placeholder="Answer here">
                <input type="text" name="choice2n" id="choice2" class="quizChoice"  placeholder="Wrong answer here">
                <input type="text" name="choice3n" id="choice3" class="quizChoice"  placeholder="Wrong answer here">
                <input type="text" name="choice4n" id="choice4" class="quizChoice"  placeholder="Wrong answer here">
                <button class="postQuizQuestion" type="submit" name="postquiz-btn">Post Question</button>
            </form>
            </div>
            
            
            <div id="rewards-layout" class="layout" style="display: none;">
                <div class="rewards-container">
                <form action="addreward.php" method="POST">
                    <input type="text" name="rewardname" id="reward1" class="reward" rows="1" placeholder="Reward Name">
                    <input type="text" name="price" id="reward2" class="reward" rows="1" placeholder="Price"></textarea>
                    <button class="postRewards" type="submit" name="addreward-btn">Add Reward</button>
                </form>
                </div>
            </div>
            
            <div id="users-layout" class="layout">
    <?php 
    $sql = "SELECT tupv_id, firstname, lastname, course_section, photo, coverphoto, points FROM user LIMIt 3";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $points = $row["points"];
            $firstname = $row["firstname"];
            $lastname = $row["lastname"];
            $fullname = $firstname . " " . $lastname;
            $course_section = $row["course_section"];
            $photo = $row["photo"];
            $coverphoto = $row["coverphoto"];
            $tupv_id = $row["tupv_id"];

            $base64Photo = base64_encode($photo);
            $photoSrc = 'data:image/jpeg;base64,' . $base64Photo;

            $base64CoverPhoto = base64_encode($coverphoto);
            $coverphotoSrc = 'data:image/jpeg;base64,' . $base64CoverPhoto;

            // Print user details inside nested divs for each user
            echo '<div class="user">';
            echo '<div class="user-info">';
            echo '<div class="user-photos">
            <style>
            .user-photos {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                overflow: hidden;
                margin-right: 10px;
            }
            .user-photos img{
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            </style>';
            echo '<img src="' . $photoSrc . '" alt="User Photo">';
            //echo '<img src="' . $coverphotoSrc . '" alt="User Cover Photo">';
            echo '</div>';
            echo '<p>TUPV ID: ' . $tupv_id . '</p>';
            echo '<p>Name: ' . $fullname . '</p>';
            echo '<p>Course Section: ' . $course_section . '</p>';
            echo '<p>Points: ' . $points . '</p>';
            echo '</div>';
            echo '<br>'; // Add line break for separation
            echo '</div>';
        }
    } else {
        echo "User data not found.";
    }
    ?>
</div>



            <div id="event-layout" class="layout" style="display: none;">
                <textarea id="event-text" class="eventText" rows="4" cols="50" placeholder="Type your event details here..." ></textarea>
                <button class="postEvent" onclick="postEvent()">Post Event</button>
            </div>
            
        </div>
    </div>
</body>
</html>
