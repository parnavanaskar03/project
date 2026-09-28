<?php

session_start();
include("../config/db.php");


/* Login Check */

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}


/* Admin or Security can update SOS */

if($_SESSION['role'] != "admin" && $_SESSION['role'] != "security"){
    header("Location: ../login.php");
    exit();
}


/* Update */

if(isset($_POST['update'])){

    if(!isset($_POST['id']) || !is_numeric($_POST['id'])){
        header("Location: sos_requests.php");
        exit();
    }

    $id = intval($_POST['id']);
    $status = trim($_POST['status']);
    $remarks = trim($_POST['remarks']);


    $allowed_status = array(
        "Pending",
        "Accepted",
        "Resolved"
    );


    if(!in_array($status, $allowed_status)){

        echo "<script>
        alert('Invalid SOS Status');
        window.location='sos_requests.php';
        </script>";

        exit();
    }


    $sql = "UPDATE sos_requests
            SET status='$status',
                remarks='$remarks'
            WHERE id='$id'";


    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('SOS Updated Successfully');
        window.location='sos_requests.php';
        </script>";

    }else{

        echo "<script>
        alert('Update Failed');
        window.location='sos_requests.php';
        </script>";

    }

    exit();
}


header("Location: sos_requests.php");
exit();

?>