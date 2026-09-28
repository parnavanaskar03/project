<?php

session_start();
include("../config/db.php");


/* Admin Login Check */

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit();
}


/* Update Profile */

if(isset($_POST['update'])){

    $id = $_SESSION['user_id'];

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = trim($_POST['department']);


    if($fullname == "" || $email == ""){

        echo "<script>
        alert('Full Name and Email are required.');
        window.location='profile.php';
        </script>";

        exit();
    }


    /* Profile Picture */

    if(!empty($_FILES['profile_pic']['name'])){

        $extension = strtolower(
            pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION)
        );

        $allowed = array("jpg","jpeg","png","webp");

        if(!in_array($extension, $allowed)){

            echo "<script>
            alert('Only JPG, JPEG, PNG and WEBP images are allowed.');
            window.location='profile.php';
            </script>";

            exit();
        }


        if($_FILES['profile_pic']['size'] > 5 * 1024 * 1024){

            echo "<script>
            alert('Profile picture must be less than 5 MB.');
            window.location='profile.php';
            </script>";

            exit();
        }


        $filename = "admin_" . $id . "_" . time() . "." . $extension;

        $tmp = $_FILES['profile_pic']['tmp_name'];

        if(move_uploaded_file($tmp, "../uploads/".$filename)){

            mysqli_query($conn,"UPDATE users SET
            fullname='$fullname',
            email='$email',
            phone='$phone',
            department='$department',
            profile_pic='$filename'
            WHERE id='$id'");

        }else{

            echo "<script>
            alert('Profile picture upload failed.');
            window.location='profile.php';
            </script>";

            exit();
        }


    }else{

        mysqli_query($conn,"UPDATE users SET
        fullname='$fullname',
        email='$email',
        phone='$phone',
        department='$department'
        WHERE id='$id'");

    }


    /* Success */

    echo "<script>
    alert('Profile Updated Successfully');
    window.location='profile.php';
    </script>";

    exit();

}

?>