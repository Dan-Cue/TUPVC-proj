<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('location:login-page.php');
    exit();
}
include "dbcon.php";

// Retrieve user data from the database
$login_id = $_SESSION['login_id'];
$sql = "SELECT points, last_claim_time, claimbool, firstname, lastname, course_section, photo, coverphoto, bio, birthday FROM user WHERE tupv_id = '$login_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $points = $row["points"];
    $firstname = $row["firstname"];
    $lastname = $row["lastname"];
    $fullname = $firstname . " " .$lastname;
    $course_section = $row["course_section"];
    $photo = $row["photo"];
    $coverphoto = $row["coverphoto"];
    $bio = $row["bio"];
    $birthday = $row["birthday"];

    $base64Photo = base64_encode($photo);
    $photoSrc = 'data:image/jpeg;base64,' . $base64Photo;

    $base64CoverPhoto = base64_encode($coverphoto);
    $coverphotoSrc = 'data:image/jpeg;base64,' . $base64CoverPhoto;

    $lastClaimTime = strtotime($row["last_claim_time"]); // Convert last claim time to Unix timestamp
    $claimbool = $row["claimbool"]; // Get the claimbool value
} else {
    echo "User data not found.";
    exit();
}

$sqlQuestion = "SELECT quiz_id, quizcontent, answer, wrong_answer1, wrong_answer2, wrong_answer3 FROM quiz ORDER BY  RAND() LIMIT 1";
$resultQuestion = $conn->query($sqlQuestion);

if ($resultQuestion->num_rows > 0) {
    $rowQuestion = $resultQuestion->fetch_assoc();
    $Question = $rowQuestion["quizcontent"];
    $Answer = $rowQuestion["answer"]; // Store the correct answer
    $wrong1 = $rowQuestion["wrong_answer1"];
    $wrong2 = $rowQuestion["wrong_answer2"];
    $wrong3 = $rowQuestion["wrong_answer3"];
    $options = array($Answer, $wrong1, $wrong2, $wrong3);
    shuffle($options);
} else {
    $Question = "No question found"; // Default value if no question is found
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HomePage</title>
    <link rel="stylesheet" type="text/css" href="homestyle.css">

    <script>
        // Function to calculate time until next 00:00 AM
        function timeUntilMidnight() {
            var now = new Date();
            var midnight = new Date();
            midnight.setHours(24, 0, 0, 0); // Set to 00:00 AM tomorrow
            var timeUntilMidnight = midnight - now;
            return timeUntilMidnight;
        }

        // Function to update timer display
        function updateTimer() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();

            // Check if current time is 00:00
            if (hours === 0 && minutes === 0 && seconds === 0) {
                // Make AJAX call to reset claimbool to TRUE
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Success, claimbool reset to TRUE
                            console.log('claimbool reset to TRUE');
                        } else {
                            // Handle error
                            console.error('Error resetting claimbool:', xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'reset_claimbool.php', true);
                xhr.send();
            }

            // Calculate time until next 00:00 AM
            var midnight = new Date(now);
            midnight.setHours(24, 0, 0, 0); // Set to 00:00 AM tomorrow
            var timeUntilMidnight = midnight - now;
            var hours = Math.floor(timeUntilMidnight / (1000 * 60 * 60));
            var minutes = Math.floor((timeUntilMidnight % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((timeUntilMidnight % (1000 * 60)) / 1000);

            // Update timer display
            var timerElement = document.getElementById("timer");
            if (timerElement) {
                timerElement.innerHTML = hours + "h " + minutes + "m " + seconds + "s ";
            }
        }


        // Update timer display every second
        setInterval(updateTimer, 1000);

        // Function to enable or disable the button based on claimbool value
        function enableButtonBasedOnClaimbool(claimbool) {
            var claimPointsButton = document.getElementById("claim_points_button");
            if (claimPointsButton) {
                claimPointsButton.disabled = !claimbool; // Enable button if claimbool is true, otherwise disable it
            }
        }

        // Function to update timer display and button state
        function updateTimerAndButtonState() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        enableButtonBasedOnClaimbool(response.claimbool); // Enable or disable button based on claimbool value
                    } else {
                        console.error('Error fetching claimbool value:', xhr.status);
                    }
                }
            };
            xhr.open('GET', 'claimbool.php', true); // Assuming you have a PHP file to retrieve claimbool value
            xhr.send();
        }

        // Update timer display and button state every second
        setInterval(updateTimerAndButtonState,1);

        // Function to handle claiming points
        function claimPoints() {
            var claimPointsButton = document.getElementById("claim_points_button");
            if (!claimPointsButton.disabled) {
                // Make AJAX request to claim points
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Display success message or handle response accordingly
                            alert("Points claimed successfully!");
                        } else {
                            // Display error message or handle error accordingly
                            alert("Error claiming points!");
                        }
                    }
                };
                xhr.open('POST', 'claim_points.php', true); // Assuming you have a PHP file to handle point claiming
                xhr.send();
            }
        }

        function checkAnswer(selectedAnswer) {
            var correctAnswer = "<?php echo $Answer; ?>";
            if (selectedAnswer === correctAnswer) {
                var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert("Points added successfully!");
                        // Optionally, you can reload the page to reflect the updated points
                        window.location.reload();
                    } else {
                        alert("Error adding points!");
                    }
                }
            };
            xhr.open('POST', 'update_points.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send(); // Send the AJAX request
            } else {
                alert("Incorrect answer. Try again!");
            }
        }



    </script>

<script>
    function openOverlay() {
        document.getElementById("overlay").style.display = "flex";
    }
    
    function closeOverlay() {
        document.getElementById("overlay").style.display = "none";
    }

    function openRedeemOverlay() {
    document.getElementById("redeem-overlay").style.display = "flex";
    }

    function closeRedeemOverlay() {
    document.getElementById("redeem-overlay").style.display = "none";
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
        <div class="icon-container">
            
        </div>
        <button class="logout-button" type="submit" onclick="window.location.href='logout.php';">Logout</button>
    </header>
    
    <div class="container">
        <div class="top-body">
            <div class="profile-showcase" style="background-image: url('<?php echo $coverphotoSrc; ?>');">
                <div class="profile-info">
                    <div class="profile-picture">
                        <img src="<?php echo $photoSrc; ?>" alt="Profile Picture">
                    </div>
                    <div class="personal-info">
                        <div class="full-name"><?php echo $fullname; ?></div>
                        <div class="course-year-section"><?php echo $course_section ?></div>
                        <div class="Bio"><?php echo $bio ?></div>
                    </div>
                </div>
                <button class="edit-profile-button" onclick="openOverlay()">Edit Profile</button>
            </div>       
            <div class="question-container">
                <div class="question-top"> Question of The Day</div>
                <div class="question-box"> 
                    <div class="question-text"><br> <?php echo $Question ?></div>
                    <br>
                    <?php
                    // Display the shuffled options as buttons
                    foreach ($options as $option) {
                        echo '<form> <button class="choice-button" onclick="checkAnswer(\'' . $option . '\')">' . $option . '</button></form>';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="contents">
            <div class="profile-options">
                <button class="profile-option-button"></button>
                <button class="profile-option-button"><img src="calendar.png"> <?php echo $birthday ?></button>
                <button class="redeem-button" onclick="openRedeemOverlay()"> <img src="gift.png"> <b> Redeem Points!</b> </button>
                <div class="points-display"> <b> Points: </b>  
                    <?php
                    // Display points retrieved from the database
                    echo '&#127775;' . $points;
                    ?> 
                </div>
                <form>
                    <button class="dailypoints-btn" type="button" onclick="claimPoints()" id="claim_points_button" <?php echo ($claimbool == 'FALSE') ? 'disabled' : ''; ?>><img src="gift.png">Daily Points</button>
                </form>
                <div class="timer"><p class="ptimer">⏳<span id="timer"></span></p></div>
            </div>
            <div class="news-feed">
                <div class="post-here">
                    <div class="user-profile">
                        <img src="<?php echo $photoSrc; ?>" alt="User Profile">
                    </div>
                    <div class="post-input">
                        <form action="post.php" method="post">
                        <textarea name="post_content" placeholder="Write your post here..." rows="4" cols="50"></textarea>
                        <br>
                        <input type="submit" name="submit_post" value="Post">
                        </form>
                    </div>
                </div>
                
            <div class="main-feed">
                <?php
                
                // Retrieve posts from the database
                $posts_query = "SELECT posts.*, user.firstname, user.lastname, COUNT(likes.like_id) AS total_likes FROM posts 
                LEFT JOIN user ON posts.tupv_id = user.tupv_id LEFT JOIN likes ON posts.post_id = likes.post_id GROUP BY posts.post_id ORDER BY posts.date_posted DESC";
                $posts_result = $conn->query($posts_query);

                if ($posts_result->num_rows > 0) {
                    // Output each post
                    while ($post_row = $posts_result->fetch_assoc()) {
                        $post_id = $post_row['post_id'];
                        $fullname = $post_row['firstname'] . ' ' . $post_row['lastname'];
                        $post_content = $post_row['postcontent'];
                        $date_posted = date('F j, Y, g:i a', strtotime($post_row['date_posted']));
                        $total_likes = $post_row['total_likes'];

                        // Retrieve comments for the post
                        $comments_query = "SELECT comments.*, user.firstname, user.lastname FROM comments LEFT JOIN user ON comments.tupv_id = user.tupv_id WHERE post_id = $post_id ORDER BY comments.timestamp DESC";
                        $comments_result = $conn->query($comments_query);

                        // Display post
                ?>
                        <div class="post">
                            <div class="post-header">
                                <div class="post-author">
                                    <?php if (!empty($profile_picture)) { ?>
                                        <img src="data:image/jpeg;base64,<?= base64_encode($profile_picture) ?>" class="author-profile-picture" alt="Profile Picture">
                                    <?php } ?>
                                    <span><?= $fullname ?></span>
                                </div>
                                <div class="post-date"><?= $date_posted ?></div>
                            </div>
                            <div class="post-content"><?= $post_content ?></div>
                            <div class="post-actions">
                                <form action="post.php" method="post">
                                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                    <input type="submit" name="like" value="Like">
                                    <span><?= $total_likes ?> likes</span>
                                </form>
                                <form action="post.php" method="post">
                                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                                    <input type="text" name="comment" placeholder="Write a comment">
                                    <input type="submit" name="submit_comment" value="Comment">
                                </form>
                            </div>
                            
                            <!-- Display comments -->
                            <div class="post-comments">
                                <?php if ($comments_result->num_rows > 0) { ?>
                                    <?php while ($comment_row = $comments_result->fetch_assoc()) { ?>
                                        <div class="comment">
                                            <span class="comment-author"><?= $comment_row['firstname'] . ' ' . $comment_row['lastname'] ?>:</span>
                                            <span class="comment-text"><?= $comment_row['comment_text'] ?></span>
                                        </div>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="no-comments">No comments yet.</div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "No posts found.";
                }
                ?>
            </div>

            </div>
            <div class="event-box">
                <div class="event-label">
                    <div class="text-event-label"> Announcements</div>
                </div>
                <div class="events"> 
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
        </div>
    </div>

    <div class="overlay" id="overlay">
        <div class="overlay-content">
            <textarea placeholder="First Name"></textarea>
            <textarea placeholder="Last Name"></textarea>
            <textarea placeholder="Course and Section"></textarea>
            <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="userId" value="1"> <!-- Example user ID -->
        <input type="file" name="newPicture" accept="image/*">
        <button name="jonathan">Upload</button>
            <button class="cancel-button" onclick="closeOverlay()">Cancel</button>
            </form>
        </div>
    </div> 
    <div class="redeem-overlay" id="redeem-overlay">
        <div class="overlay-content">
            <div class="points-displayy">POINTS: <?php echo $points; ?></div>
            <div class="reward-buttons">
                <?php 
                $sqlRewards = "SELECT reward_name, price FROM rewards ORDER BY price DESC";
                $resultRewards = $conn->query($sqlRewards);

                if($resultRewards->num_rows > 0){
                    while($rowRewards = $resultRewards->fetch_assoc()){
                        $Rewards = $rowRewards["reward_name"];
                        $Price = $rowRewards["price"];
                        echo '<button class="reward-button"><img src="gift.png">' . $Rewards . ' Price: ' . $Price . '-points</button>';
                    }
                } else {
                    echo "No rewards found.";
                }

                ?>
            </div>
            <button class="close-redeem-button" onclick="closeRedeemOverlay()">Close</button>
        </div>
    </div>
</body>
</html>
