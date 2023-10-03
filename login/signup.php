<?php
    include("../connection.php");    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Something was posted
        $username = $_POST['username'];
        $password = $_POST['password'];   
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);     

        $checkUsernameQuery = "SELECT * FROM users WHERE username = ?";
        $stmt2 = mysqli_prepare($con, $checkUsernameQuery);
        mysqli_stmt_bind_param($stmt2, "s", $username);
        mysqli_stmt_execute($stmt2);
        $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt2));

        if($result) {
            echo "<div class='password-guidelines'>";
            echo "Username already taken";
            echo "</div>";
        } else {
            if(!empty($username) && !empty($password)) {
                $query = "insert into Users (username, password) values (?, ?)";
    
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, "ss", $username, $hashedpassword);
                mysqli_stmt_execute($stmt);
    
                // echo "<div class='password-guidelines'>";
                // echo "Account created successfully.";
                // echo "</div>";
            } else {
                echo "<div class='password-guidelines'>";
                echo "Please enter some valid Information";
                echo "</div>";
            }
        }        
    }

?>