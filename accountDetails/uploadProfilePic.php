<?php
session_start();
include("../connection.php");
include("../functions.php");

    if(isset($_FILES['profile-pic'])) {
        deletePrevDp($con, $_SESSION['user_id']);

        $file = $_FILES['profile-pic'];

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExplode = explode(".", $fileName);
        $fileExt = strtolower(end($fileExplode));
        $fileNameWoExt = strtolower(($fileExplode[0]));

        $allowedExt = array("jpeg", "jpg", "png");

        if(!in_array($fileExt, $allowedExt)) {
            echo "This type is not allowed";
        } else {
            if($fileError !== 0) {
                echo "There was an error uploading your file";
            } else {
                if($fileSize > 1000000) {
                    echo "File Size exceeding limit";
                } else {
                    $fileNewName = "DP-" . $fileNameWoExt . uniqid('', true) . "." . $fileExt;
                    $fileDestination = 'ProfilePics/' . $fileNewName;
                    $uploadFileDestination = "ProfilePics/" . $fileNewName;
                    if(move_uploaded_file($fileTmpName, $fileDestination)) {
                        $id = $_SESSION['user_id'];

                        $query = 'UPDATE Users SET profile_picture_url = ? WHERE user_id = ?';
                        $stmt = mysqli_prepare($con, $query);
                        mysqli_stmt_bind_param($stmt, "si", $uploadFileDestination, $id);
                        if(!mysqli_stmt_execute($stmt)) {
                            echo "There was an error uploading your pic";
                        } else {
                            echo "successfull yayy!!";
                        }
                    } else {
                        print_r(error_get_last());
                    }
                }
            }
        }
    }
?>