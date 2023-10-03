<?php
// This file is used to update the interests after changes are made to interest container, and closed

include "../connection.php";
include "../functions.php";

$userId = $_POST['userId'];

displayInterests($con, $userId);

?>