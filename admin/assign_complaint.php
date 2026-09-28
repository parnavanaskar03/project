<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="admin"){
    header("Location: ../login.php");
    exit();
}


/* =========================
   ASSIGN COMPLAINT
========================= */

if(isset($_POST['assign'])){

    $id = $_POST['id'];
    $assigned_to = $_POST['assigned_to'];

    $sql = "UPDATE complaints
            SET assigned_to='$assigned_to'
            WHERE id='$id'";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Complaint Assigned Successfully');
        window.location='assign_complaint.php';
        </script>";

    }else{

        echo "<script>
        alert('Assignment Failed');
        </script>";

    }

}


/* =========================
   GET COMPLAINTS
========================= */

$query = mysqli_query($conn,"
    SELECT complaints.*, 
           users.fullname,
           users.student_id
    FROM complaints
    LEFT JOIN users
    ON complaints.user_id = users.id
    ORDER BY complaints.id DESC
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Manage Complaints</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial,sans-serif;
    background:#f4f7fb;
    color:#334155;
}

/* PAGE */

.complaint-page{
    min-height:100vh;
    padding:35px 20px;
}

.complaint-container{
    max-width:1200px;
    margin:auto;
}

/* HEADER */

.complaint-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.complaint-header h1{
    color:#172554;
    font-size:28px;
}

.complaint-header p{
    color:#64748b;
    font-size:14px;
    margin-top:7px;
}

.back-btn{
    text-decoration:none;
    background:#172554;
    color:white;
    padding:12px 18px;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
}

.back-btn:hover{
    background:#2563eb;
}

/* INFO */

.info-box{
    background:#eff6ff;
    border-left:5px solid #2563eb;
    padding:16px 18px;
    border-radius:10px;
    margin-bottom:22px;
}

.info-box strong{
    color:#172554;
    font-size:14px;
}

.info-box p{
    color:#475569;
    font-size:12px;
    margin-top:5px;
    line-height:1.5;
}

/* COMPLAINT LIST */

.complaint-list{
    display:flex;
    flex-direction:column;
    gap:18px;
}

/* CARD */

.complaint-card{
    background:white;
    border-radius:16px;
    padding:22px;
    box-shadow:0 5px 18px rgba(0,0,0,.07);
    border-left:5px solid #2563eb;
}

/* TOP */

.complaint-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:15px;
    padding-bottom:17px;
    border-bottom:1px solid #eef2f7;
}

.complaint-title-area{
    flex:1;
}

.complaint-id{
    color:#94a3b8;
    font-size:11px;
}

.complaint-title-area h2{
    color:#172554;
    font-size:19px;
    margin-top:5px;
}

.complaint-category{
    display:inline-block;
    margin-top:8px;
    background:#dbeafe;
    color:#1d4ed8;
    padding:5px 10px;
    border-radius:15px;
    font-size:11px;
    font-weight:600;
}

/* STATUS */

.status-badge{
    padding:7px 13px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
    white-space:nowrap;
}

.status-pending{
    background:#fef3c7;
    color:#92400e;
}

.status-progress{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-resolved{
    background:#dcfce7;
    color:#15803d;
}

.status-other{
    background:#e2e8f0;
    color:#475569;
}

/* STUDENT */

.student-info{
    display:flex;
    gap:30px;
    flex-wrap:wrap;
    margin-top:17px;
}

.student-info div{
    background:#f8fafc;
    padding:11px 14px;
    border-radius:9px;
    min-width:180px;
}

.student-info small{
    display:block;
    color:#94a3b8;
    font-size:10px;
    margin-bottom:4px;
}

.student-info strong{
    color:#475569;
    font-size:12px;
}

/* DESCRIPTION */

.description{
    margin-top:18px;
}

.description strong{
    color:#334155;
    font-size:13px;
}

.description p{
    color:#64748b;
    font-size:13px;
    line-height:1.6;
    margin-top:7px;
}

/* IMAGE */

.complaint-image{
    margin-top:17px;
}

.complaint-image strong{
    color:#334155;
    font-size:13px;
}

.complaint-image img{
    display:block;
    margin-top:8px;
    width:150px;
    height:110px;
    object-fit:cover;
    border-radius:9px;
    border:1px solid #e2e8f0;
}

/* ASSIGN SECTION */

.assign-section{
    margin-top:20px;
    padding-top:18px;
    border-top:1px solid #eef2f7;
}

.assign-section label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:600;
    margin-bottom:8px;
}

.assign-form{
    display:flex;
    gap:10px;
    align-items:center;
}

.assign-form select{
    flex:1;
    max-width:400px;
    height:43px;
    border:1px solid #d7dee8;
    border-radius:8px;
    padding:0 12px;
    background:white;
    color:#334155;
    outline:none;
    font-size:13px;
}

.assign-form select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.10);
}

.assign-btn{
    height:43px;
    border:none;
    border-radius:8px;
    background:#2563eb;
    color:white;
    padding:0 20px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
}

.assign-btn:hover{
    background:#1d4ed8;
}

/* CURRENT ASSIGNMENT */

.current-assignment{
    margin-top:10px;
    color:#64748b;
    font-size:11px;
}

.current-assignment strong{
    color:#334155;
}

/* DATE */

.complaint-date{
    margin-top:15px;
    color:#94a3b8;
    font-size:11px;
}

/* EMPTY */

.empty-box{
    background:white;
    border-radius:16px;
    padding:60px 20px;
    text-align:center;
    box-shadow:0 5px 18px rgba(0,0,0,.07);
}

.empty-icon{
    width:70px;
    height:70px;
    margin:auto;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
}

.empty-box h2{
    color:#172554;
    margin-top:18px;
    font-size:21px;
}

.empty-box p{
    color:#64748b;
    font-size:13px;
    margin-top:7px;
}

/* MOBILE */

@media(max-width:700px){

    .complaint-page{
        padding:20px 12px;
    }

    .complaint-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .complaint-header h1{
        font-size:24px;
    }

    .back-btn{
        width:100%;
        text-align:center;
    }

    .complaint-card{
        padding:18px;
    }

    .complaint-top{
        flex-direction:column;
    }

    .student-info{
        flex-direction:column;
        gap:10px;
    }

    .student-info div{
        width:100%;
    }

    .assign-form{
        flex-direction:column;
        align-items:stretch;
    }

    .assign-form select{
        max-width:none;
        width:100%;
    }

    .assign-btn{
        width:100%;
    }

}

</style>

</head>

<body>

<div class="complaint-page">

<div class="complaint-container">


    <!-- HEADER -->

    <div class="complaint-header">

        <div>

            <h1>
                Complaint Management
            </h1>

            <p>
                View, manage and assign student complaints
            </p>

        </div>

        <a
            href="dashboard.php"
            class="back-btn"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- INFO -->

    <div class="info-box">

        <strong>
            📋 Admin Complaint Management
        </strong>

        <p>
            Review student complaints and assign each issue
            to the appropriate campus team for further action.
        </p>

    </div>


    <!-- COMPLAINTS -->

    <div class="complaint-list">

    <?php

    if(mysqli_num_rows($query) > 0){

        while($row = mysqli_fetch_assoc($query)){

            $status = strtolower($row['status']);

            if($status == "pending"){
                $status_class = "status-pending";
            }
            elseif($status == "in progress"){
                $status_class = "status-progress";
            }
            elseif($status == "resolved" || $status == "completed"){
                $status_class = "status-resolved";
            }
            else{
                $status_class = "status-other";
            }

    ?>

        <div class="complaint-card">


            <!-- TOP -->

            <div class="complaint-top">

                <div class="complaint-title-area">

                    <span class="complaint-id">
                        Complaint #<?php echo htmlspecialchars($row['id']); ?>
                    </span>

                    <h2>
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h2>

                    <span class="complaint-category">
                        <?php echo htmlspecialchars($row['category']); ?>
                    </span>

                </div>


                <span class="status-badge <?php echo $status_class; ?>">

                    <?php echo htmlspecialchars($row['status']); ?>

                </span>

            </div>


            <!-- STUDENT INFO -->

            <div class="student-info">

                <div>

                    <small>
                        Student Name
                    </small>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $row['fullname'] ?? 'Unknown'
                        );
                        ?>
                    </strong>

                </div>


                <div>

                    <small>
                        Student ID
                    </small>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $row['student_id'] ?? 'N/A'
                        );
                        ?>
                    </strong>

                </div>

            </div>


            <!-- DESCRIPTION -->

            <div class="description">

                <strong>
                    Complaint Description
                </strong>

                <p>
                    <?php
                    echo nl2br(
                        htmlspecialchars($row['description'])
                    );
                    ?>
                </p>

            </div>


            <!-- IMAGE -->

            <?php

            if(!empty($row['image'])){

            ?>

            <div class="complaint-image">

                <strong>
                    Attached Image
                </strong>

                <img
                    src="../uploads/<?php
                    echo htmlspecialchars($row['image']);
                    ?>"
                    alt="Complaint Image"
                >

            </div>

            <?php } ?>


            <!-- ASSIGN -->

            <div class="assign-section">

                <label>
                    Assign Complaint To
                </label>


                <form
                    method="POST"
                    class="assign-form"
                >

                    <input
                        type="hidden"
                        name="id"
                        value="<?php
                        echo htmlspecialchars($row['id']);
                        ?>"
                    >


                    <select
                        name="assigned_to"
                        required
                    >

                        <option value="">
                            Select Responsible Team
                        </option>

                        <option
                            value="teacher"
                            <?php
                            if($row['assigned_to']=="teacher")
                                echo "selected";
                            ?>
                        >
                            Teacher
                        </option>

                        <option
                            value="hod"
                            <?php
                            if($row['assigned_to']=="hod")
                                echo "selected";
                            ?>
                        >
                            HOD
                        </option>

                        <option
                            value="warden"
                            <?php
                            if($row['assigned_to']=="warden")
                                echo "selected";
                            ?>
                        >
                            Warden
                        </option>

                        <option
                            value="electrician"
                            <?php
                            if($row['assigned_to']=="electrician")
                                echo "selected";
                            ?>
                        >
                            Electrician
                        </option>

                        <option
                            value="security"
                            <?php
                            if($row['assigned_to']=="security")
                                echo "selected";
                            ?>
                        >
                            Security
                        </option>

                    </select>


                    <button
                        type="submit"
                        name="assign"
                        class="assign-btn"
                    >
                        Assign Complaint
                    </button>

                </form>


                <?php

                if(!empty($row['assigned_to'])){

                ?>

                <div class="current-assignment">

                    Currently Assigned To:
                    <strong>
                        <?php
                        echo htmlspecialchars(
                            ucfirst($row['assigned_to'])
                        );
                        ?>
                    </strong>

                </div>

                <?php } ?>


            </div>


            <!-- DATE -->

            <div class="complaint-date">

                Submitted:
                <?php
                echo htmlspecialchars(
                    $row['created_at']
                );
                ?>

            </div>


        </div>

    <?php

        }

    }else{

    ?>

        <div class="empty-box">

            <div class="empty-icon">
                📋
            </div>

            <h2>
                No Complaints Found
            </h2>

            <p>
                There are currently no student complaints to manage.
            </p>

        </div>

    <?php

    }

    ?>

    </div>

</div>

</div>

</body>

</html>