<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['update'])){

    $id = $_POST['id'];
    $status = $_POST['status'];

    $sql = "UPDATE complaints SET status='$status' WHERE id='$id'";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Status Updated Successfully');
        window.location='dashboard.php';
        </script>";

    }else{

        echo "<script>
        alert('Update Failed');
        </script>";

    }

}

?>