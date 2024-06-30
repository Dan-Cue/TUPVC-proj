<?php
include('dbcon.php');

// Update total likes for each post
$update_likes_query = "UPDATE posts p
                      LEFT JOIN (
                          SELECT post_id, COUNT(like_id) AS total_likes
                          FROM likes l
                          GROUP BY post_id
                      ) l ON p.post_id = l.post_id
                      SET p.likes = IFNULL(l.total_likes, 0)";
mysqli_query($conn, $update_likes_query);

// Update total comments for each post
$update_comments_query = "UPDATE posts p
                         LEFT JOIN (
                             SELECT post_id, COUNT(comment_id) AS total_comments
                             FROM comments
                             GROUP BY post_id
                         ) c ON p.post_id = c.post_id
                         SET p.comments = IFNULL(c.total_comments, 0)";
mysqli_query($conn, $update_comments_query);

// Optionally, you can include error handling
if (mysqli_error($conn)) {
    echo "Error updating posts table: " . mysqli_error($conn);
} else {
    echo "Posts table updated successfully!";
}
?>
