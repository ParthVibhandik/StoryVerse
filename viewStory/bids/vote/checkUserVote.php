<?php
session_start();
include "../../../connection.php";
include "../../../functions.php";

    $userId = $_SESSION['user_id'];
    $bidId = $_POST['bidId'];

    echo checkUserVote($con, $bidId, $userId);

?>