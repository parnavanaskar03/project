<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "electrician"){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['update'])){

    $fullname = mysqli_real_escape_string(
        $conn,
        $_POST['fullname']
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $_POST['phone']
    );

    $department = mysqli_real_escape_string(
        $conn,
        $_POST['department']
    );


    /* Check duplicate email */

    $check_sql = "
        SELECT id
        FROM users
        WHERE email='$email'
        AND id!='$user_id'
    ";

    $check_result = mysqli_query(
        $conn,
        $check_sql
    );


    if(mysqli_num_rows($check_result) > 0){

        echo "<script>
        alert('This email is already used by another user.');
        window.location='profile.php';
        </script>";

        exit();
    }


    /* Update basic profile information */

    $sql = "
        UPDATE users
        SET
        fullname='$fullname',
        email='$email',
        phone='$phone',
        department='$department'
        WHERE id='$user_id'
    ";

    if(!mysqli_query($conn,$sql)){

        echo "<script>
        alert('Profile update failed.');
        window.location='profile.php';
        </script>";

        exit();
    }


    /* Profile Picture Upload */

    if(
        isset($_FILES['profile_pic']) &&
        $_FILES['profile_pic']['error'] == 0
    ){

        $file_name = $_FILES['profile_pic']['name'];

        $file_tmp = $_FILES['profile_pic']['tmp_name'];

        $file_size = $_FILES['profile_pic']['size'];


        $file_ext = strtolower(
            pathinfo(
                $file_name,
                PATHINFO_EXTENSION
            )
        );


        $allowed_ext = array(
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp"
        );


        /* Check extension */

        if(!in_array($file_ext,$allowed_ext)){

            echo "<script>
            alert('Only JPG, JPEG, PNG, GIF and WEBP images are allowed.');
            window.location='profile.php';
            </script>";

            exit();
        }


        /* Check file size */

        if($file_size > 5 * 1024 * 1024){

            echo "<script>
            alert('Image size must be less than 5 MB.');
            window.location='profile.php';
            </script>";

            exit();
        }


        /* New file name */

        $new_file_name =
            "electrician_" .
            $user_id .
            "_" .
            time() .
            "." .
            $file_ext;


        $upload_dir = "../uploads/";


        /* Create uploads folder if missing */

        if(!is_dir($upload_dir)){

            mkdir(
                $upload_dir,
                0777,
                true
            );
        }


        $upload_path =
            $upload_dir .
            $new_file_name;


        /* Upload */

        if(
            move_uploaded_file(
                $file_tmp,
                $upload_path
            )
        ){

            $pic_sql = "
                UPDATE users
                SET profile_pic='$new_file_name'
                WHERE id='$user_id'
            ";

            mysqli_query(
                $conn,
                $pic_sql
            );

        }else{

            echo "<script>
            alert('Profile updated, but image upload failed.');
            window.location='profile.php';
            </script>";

            exit();
        }
    }


    /* Success */

    echo "<script>

    alert('Electrician Profile Updated Successfully!');

    window.location='profile.php';

    </script>";

    exit();
}


header("Location: profile.php");

exit();

?>