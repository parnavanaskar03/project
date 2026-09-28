<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="electrician"){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $sql = "UPDATE complaints
            SET status='$status',
                remarks='$remarks'
            WHERE id='$id'";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Status & Remarks Updated Successfully');
        window.location='dashboard.php';
        </script>";

    }else{

        echo "<script>
        alert('Update Failed');
        </script>";

    }

}

?>