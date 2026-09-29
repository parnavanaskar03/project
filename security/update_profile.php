<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "security"){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['update_profile'])){

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $department = trim($_POST['department']);

    // Check duplicate email
    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM users WHERE email = ? AND id != ?"
    );

    mysqli_stmt_bind_param($check, "si", $email, $user_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if(mysqli_stmt_num_rows($check) > 0){

        echo "<script>
        alert('Email already exists!');
        window.location='profile.php';
        </script>";

        exit();
    }

    mysqli_stmt_close($check);

    // Update basic profile details
    $sql = "UPDATE users
            SET fullname = ?,
                email = ?,
                phone = ?,
                department = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $fullname,
        $email,
        $phone,
        $department,
        $user_id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    // Profile picture upload
    if(isset($_FILES['profile_pic']) &&
       $_FILES['profile_pic']['error'] == 0){

        $file_name = $_FILES['profile_pic']['name'];
        $file_tmp = $_FILES['profile_pic']['tmp_name'];
        $file_size = $_FILES['profile_pic']['size'];

        $ext = strtolower(
            pathinfo($file_name, PATHINFO_EXTENSION)
        );

        $allowed_ext = array(
            "jpg",
            "jpeg",
            "png",
            "gif",
            "webp"
        );

        if(!in_array($ext, $allowed_ext)){

            echo "<script>
            alert('Invalid image format!');
            window.location='profile.php';
            </script>";

            exit();
        }

        if($file_size > 5 * 1024 * 1024){

            echo "<script>
            alert('Image size must be below 5MB!');
            window.location='profile.php';
            </script>";

            exit();
        }

        $new_name =
            "security_" .
            $user_id .
            "_" .
            time() .
            "." .
            $ext;

        $upload_path = "../uploads/" . $new_name;

        if(move_uploaded_file($file_tmp, $upload_path)){

            $update_pic = mysqli_prepare(
                $conn,
                "UPDATE users SET profile_pic = ? WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $update_pic,
                "si",
                $new_name,
                $user_id
            );

            mysqli_stmt_execute($update_pic);
            mysqli_stmt_close($update_pic);
        }
    }

    echo "<script>
    alert('Security Profile Updated Successfully!');
    window.location='profile.php';
    </script>";

}
?>