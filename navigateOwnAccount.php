<?php
// This file is used to navigate the user to its own account, when the user-profile button on header is clicked

session_start();

$userId = $_SESSION['user_id'];
$baseUrl = $_POST['baseUrl'];

$destinationUrl = $baseUrl . "/accountDetails/accountDetails.php?userId=" . $userId;

echo $destinationUrl;

exit;

?>