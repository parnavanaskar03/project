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

    $status = trim($_POST['status'] ?? '');
    $remarks = trim($_POST['remarks'] ?? '');


    /* Allowed Status */

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


    /* Check SOS exists */

    $check_sql = "SELECT id
                  FROM sos_requests
                  WHERE id = ?";

    $check_stmt = mysqli_prepare($conn, $check_sql);

    mysqli_stmt_bind_param(
        $check_stmt,
        "i",
        $id
    );

    mysqli_stmt_execute($check_stmt);

    $check_result = mysqli_stmt_get_result($check_stmt);

    if(mysqli_num_rows($check_result) == 0){

        mysqli_stmt_close($check_stmt);

        echo "<script>
        alert('SOS Request Not Found');
        window.location='sos_requests.php';
        </script>";

        exit();
    }

    mysqli_stmt_close($check_stmt);


    /* Update SOS */

    $sql = "UPDATE sos_requests
            SET status = ?,
                remarks = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssi",
        $status,
        $remarks,
        $id
    );


    if(mysqli_stmt_execute($stmt)){

        mysqli_stmt_close($stmt);

        echo "<script>
        alert('SOS Updated Successfully');
        window.location='sos_requests.php';
        </script>";

    }else{

        mysqli_stmt_close($stmt);

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