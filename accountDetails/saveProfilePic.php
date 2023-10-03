<?php
// This file is used to change the profile pic of the user
// Inputs the bg and the fg created by the user, and forwards to database
session_start();

include "../connection.php";

$bg = $_POST['bg'];
$fg = $_POST['fg'];
preg_match('/url\("([^"]+)"\)/', $fg, $fgLink);

$userId = $_SESSION['user_id'];

$query = "UPDATE Users SET profile_picture_url = ?, profile_picture_bg = ? WHERE user_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ssi", $fgLink[1], $bg, $userId);
mysqli_stmt_execute($stmt);

?>