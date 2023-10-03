<?php
include "../connection.php";
session_start();

$userId = $_SESSION['user_id'];

$query = "UPDATE Users 
    SET 
    username = 'storyverse user',
    name = '0',
    email = '0',
    password = '0',
    about = '0',
    profile_picture_url = '0',
    registration_date = '0-0-0000'
    WHERE user_id = ?";

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);

echo "successfully deleted";

?>