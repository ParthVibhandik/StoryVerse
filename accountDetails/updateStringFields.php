<?php
session_start();

include "../connection.php";
include "../functions.php";

$id = $_SESSION['user_id'];
$field = $_POST['field'];
$change = $_POST['change'];


$queryUsername = "UPDATE Users SET " . $field . " = ? WHERE user_id = ?";
$stmtUsername = mysqli_prepare($con, $queryUsername);
mysqli_stmt_bind_param($stmtUsername, "si", $change, $id);
mysqli_stmt_execute($stmtUsername);

?>