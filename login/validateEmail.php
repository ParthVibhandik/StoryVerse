<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Put Email Validation in this if statement
    if(true) {
        // next button
        echo "<div id='next-signup-name'>";
            echo "<img src='Assets/scrollButton.png'>";
            echo "<p>NEXT</p>";
        echo "</div>";

    // Handle the if not validated email block here
    } // else {
    //     echo "<div class='password-guidelines'>";
    //     echo "A password should have uppercase letter and a number";
    //     echo "</div>";
    // }

} // else {
// echo "<div class='password-guidelines'></div>";
// }

?>