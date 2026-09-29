<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "hod"){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $user_id = $_SESSION['user_id'];

    /* Get HOD Department */

    $hod_query = mysqli_query($conn, "
        SELECT department
        FROM users
        WHERE id='$user_id'
    ");

    $hod = mysqli_fetch_assoc($hod_query);

    $department = $hod['department'];


    /* Update only complaint from HOD's department */

    $sql = "UPDATE complaints c
            INNER JOIN users u
            ON c.user_id = u.id
            SET c.status='$status',
                c.remarks='$remarks'
            WHERE c.id='$id'
            AND u.department='$department'";


    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Status & Remarks Updated Successfully');
        window.location='dashboard.php';
        </script>";

    }else{

        echo "<script>
        alert('Update Failed');
        window.location='dashboard.php';
        </script>";

    }

}

?>