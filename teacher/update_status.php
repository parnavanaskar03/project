<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "teacher"){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    // Logged-in teacher ID
    $teacher_id = $_SESSION['user_id'];

    // Only update complaints assigned to this teacher
    $sql = "UPDATE complaints
            SET status = ?,
                remarks = ?
            WHERE id = ?
            AND assigned_to = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssii",
        $status,
        $remarks,
        $id,
        $teacher_id
    );

    if(mysqli_stmt_execute($stmt)){

        if(mysqli_stmt_affected_rows($stmt) > 0){

            echo "<script>
            alert('Status & Remarks Updated Successfully');
            window.location='dashboard.php';
            </script>";

        }else{

            echo "<script>
            alert('Complaint not found or not assigned to you.');
            window.location='dashboard.php';
            </script>";

        }

    }else{

        echo "<script>
        alert('Update Failed');
        window.location='dashboard.php';
        </script>";

    }

    mysqli_stmt_close($stmt);
}

?>