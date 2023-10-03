<?php
include "../connection.php";

$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];

$query = "UPDATE Users SET name = ?, email = ? WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "sss", $name, $email, $username);
mysqli_stmt_execute($stmt);

?>