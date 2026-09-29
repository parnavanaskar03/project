<?php

session_start();
include("../config/db.php");


/* Check Login */

if(!isset($_SESSION['user_id'])){

    header("Location: ../login.php");
    exit();

}


/* Check HOD */

if($_SESSION['role'] != "hod"){

    header("Location: ../login.php");
    exit();

}


if(isset($_POST['assign'])){


    $complaint_id = mysqli_real_escape_string(
        $conn,
        $_POST['complaint_id']
    );


    $teacher_id = mysqli_real_escape_string(
        $conn,
        $_POST['assigned_to']
    );


    $hod_id = $_SESSION['user_id'];


    /* Get HOD Department */

    $hod_query = mysqli_query($conn, "

        SELECT department

        FROM users

        WHERE id='$hod_id'

        AND role='hod'

    ");


    $hod = mysqli_fetch_assoc($hod_query);


    if(!$hod){

        echo "<script>

            alert('HOD account not found.');

            window.location='dashboard.php';

        </script>";

        exit();

    }


    $department = $hod['department'];


    /* Check Selected Teacher */

    $teacher_query = mysqli_query($conn, "

        SELECT id, fullname

        FROM users

        WHERE id='$teacher_id'

        AND role='teacher'

        AND department='$department'

    ");


    if(mysqli_num_rows($teacher_query) == 0){

        echo "<script>

            alert('Invalid Teacher selected.');

            window.location='dashboard.php';

        </script>";

        exit();

    }


    /* Check Complaint belongs to HOD Department */

    $complaint_query = mysqli_query($conn, "

        SELECT complaints.id

        FROM complaints

        INNER JOIN users

        ON complaints.user_id = users.id

        WHERE complaints.id='$complaint_id'

        AND users.department='$department'

    ");


    if(mysqli_num_rows($complaint_query) == 0){

        echo "<script>

            alert('This complaint does not belong to your department.');

            window.location='dashboard.php';

        </script>";

        exit();

    }


    /* Assign Complaint */

    $sql = "

        UPDATE complaints

        SET assigned_to='$teacher_id'

        WHERE id='$complaint_id'

    ";


    if(mysqli_query($conn, $sql)){

        echo "<script>

            alert('Complaint Assigned Successfully!');

            window.location='dashboard.php';

        </script>";

        exit();

    }

    else{

        echo "<script>

            alert('Complaint Assignment Failed.');

            window.location='dashboard.php';

        </script>";

        exit();

    }

}


header("Location: dashboard.php");
exit();

?>