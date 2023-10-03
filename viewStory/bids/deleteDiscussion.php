<!-- This file is called from bids/viewBids.js -->
<!-- This file is used to delete a specific message in the BIDS discussion  -->
<!-- This file receives message id to do its job through post method -->

<?php
include "../../connection.php";

$messageId = $_POST['messageId'];

$query = "DELETE FROM Bid_discussion WHERE message_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $messageId);
mysqli_stmt_execute($stmt);

echo "deleted successfully";

?>