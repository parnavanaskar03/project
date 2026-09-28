<?php

session_start();
include("../config/db.php");

/* Admin login check */

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit();
}


/* User ID check */

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: manage_users.php");
    exit();
}

$id = intval($_GET['id']);


/* Admin account cannot be deleted */

$sql = "DELETE FROM users
        WHERE id='$id'
        AND role!='admin'";

if(mysqli_query($conn, $sql)){

    echo "<script>
        alert('User Deleted Successfully');
        window.location='manage_users.php';
    </script>";

}else{

    echo "<script>
        alert('Failed to Delete User');
        window.location='manage_users.php';
    </script>";
}

exit();

?>