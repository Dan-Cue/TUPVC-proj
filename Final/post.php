<?php
session_start(); // Start the session
include('dbcon.php');

if(isset($_POST['submit_post'])) {
    // Check if post content is provided
    if(!empty($_POST['post_content'])) {
        $post_content = $_POST['post_content'];
        
        // Assuming you have a session variable for user ID
        $tupv_id = $_SESSION['login_id'];

        // Insert the post into the database
        $sql = "INSERT INTO posts (postcontent, date_posted, tupv_id) VALUES ('$post_content', NOW(), '$tupv_id')";
         
        if(mysqli_query($conn,$sql)) {
            echo "Post added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Post content is required.";
    }
}

// Like functionality
if (isset($_POST['like'])) {
    $post_id = $_POST['post_id'];
    $tupv_id = $_SESSION['login_id'];

    // Check if the user already liked the post
    $check_like_query = "SELECT * FROM likes WHERE post_id = $post_id AND tupv_id = $tupv_id";
    $check_like_result = mysqli_query($conn, $check_like_query);

    if (mysqli_num_rows($check_like_result) == 0) {
        // If the user hasn't liked the post yet, insert the like
        $insert_like_query = "INSERT INTO likes (post_id, tupv_id) VALUES ($post_id, $tupv_id)";
        mysqli_query($conn, $insert_like_query);

        // Update post statistics
        include('update_post_stats.php');
    }
}

// Comment functionality
if (isset($_POST['submit_comment'])) {
    $post_id = $_POST['post_id'];
    $tupv_id = $_SESSION['login_id'];
    $comment_text = $_POST['comment'];

    // Insert the comment into the database
    $insert_comment_query = "INSERT INTO comments (post_id, tupv_id, comment_text) VALUES ($post_id, $tupv_id, '$comment_text')";
    mysqli_query($conn, $insert_comment_query);

    // Update post statistics
    include('update_post_stats.php');
}

// Redirect back to home.php after handling likes and comments
header('Location: home.php');
exit();
?>
