<?php
session_start();
include "../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Something was posted
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $query = "SELECT user_id, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $userData = mysqli_fetch_assoc($result);

            // Verify the password
            if (password_verify($password, $userData['password'])) {  //check for the hashed password 
                $_SESSION['user_id'] = $userData['user_id'];
                echo "success";
                die;
            } else {
                echo "Password does not match!";
            }
        } else {
            echo "No user found";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Please enter valid credentials";
    }
}
?>
