<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['currentPassword'];

    if(strlen($currentPassword > 1)) {
        if(strlen($currentPassword) < 7) {
            echo "<div class='password-guidelines'>";
            echo "Password too short";
            echo "</div>";
        } else {
            $uppercaseRegex = '/[A-Z]/';
            $numberRegex = '/[0-9]/';
            
            if(preg_match($uppercaseRegex, $currentPassword) && preg_match($numberRegex, $currentPassword)) {
                echo "<div id='next-signup'>";
                    echo "<img src='Assets/scrollButton.png'>";
                    echo "<p>NEXT</p>";
                echo "</div>";
            } else {
                echo "<div class='password-guidelines'>";
                echo "A password should have uppercase letter and a number";
                echo "</div>";
            }
        }
    } else {
        echo "<div class='password-guidelines'></div>";
    }
}

?>