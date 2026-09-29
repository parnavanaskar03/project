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


/* Security Information */

$user_query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($user_query);

if(!$user){
    die("User profile not found.");
}


/* Assigned Complaints */

$query = mysqli_query($conn, "
    SELECT complaints.*, users.fullname
    FROM complaints
    INNER JOIN users
    ON complaints.user_id = users.id
    WHERE complaints.assigned_to='security'
    ORDER BY complaints.id DESC
");


/* Complaint Statistics */

$total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='security'
"))['total'];

$pending = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='security'
    AND status='Pending'
"))['total'];

$progress = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='security'
    AND status='In Progress'
"))['total'];

$resolved = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='security'
    AND status='Resolved'
"))['total'];


/* SOS Statistics */

$sos_total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM sos_requests
"))['total'];

$sos_pending = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM sos_requests
    WHERE status='Pending'
"))['total'];

$sos_accepted = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM sos_requests
    WHERE status='Accepted'
"))['total'];

$sos_resolved = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM sos_requests
    WHERE status='Resolved'
"))['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Security Dashboard - Campus Care</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f7fb;
    color:#1e293b;
}


/* SIDEBAR */

.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:245px;
    height:100vh;
    background:#172554;
    color:white;
    padding:22px 15px;
    overflow-y:auto;
}

.logo{
    font-size:22px;
    font-weight:bold;
    text-align:center;
    margin-bottom:20px;
}

.logo span{
    color:#60a5fa;
}


/* PROFILE */

.sidebar-profile{
    text-align:center;
    padding:10px 5px 20px;
    border-bottom:1px solid rgba(255,255,255,0.15);
    margin-bottom:20px;
}

.sidebar-profile img,
.sidebar-profile-placeholder{
    width:75px;
    height:75px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #93c5fd;
    margin-bottom:10px;
}

.sidebar-profile-placeholder{
    margin-left:auto;
    margin-right:auto;
    background:#dbeafe;
    color:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    font-weight:bold;
}

.sidebar-profile h3{
    font-size:15px;
    margin-bottom:5px;
}

.sidebar-profile p{
    font-size:12px;
    color:#bfdbfe;
}


/* MENU */

.menu-title{
    font-size:12px;
    color:#93c5fd;
    margin:18px 12px 8px;
    text-transform:uppercase;
}

.sidebar a{
    display:block;
    text-decoration:none;
    color:#dbeafe;
    padding:12px 14px;
    margin:5px 0;
    border-radius:8px;
    font-size:14px;
    transition:0.2s;
}

.sidebar a:hover,
.sidebar a.active{
    background:#2563eb;
    color:white;
}

.logout{
    margin-top:25px !important;
    background:#991b1b;
}

.logout:hover{
    background:#dc2626 !important;
}


/* MAIN */

.main{
    margin-left:245px;
    min-height:100vh;
}


/* TOPBAR */

.topbar{
    height:70px;
    background:white;
    border-bottom:1px solid #e2e8f0;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
}

.topbar h2{
    font-size:20px;
}

.topbar-right{
    color:#64748b;
    font-size:14px;
}


/* CONTENT */

.content{
    padding:30px;
}


/* WELCOME */

.welcome{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:25px;
    border-radius:14px;
    margin-bottom:25px;
}

.welcome h1{
    font-size:25px;
    margin-bottom:8px;
}

.welcome p{
    color:#dbeafe;
    font-size:14px;
}


/* STATS */

.stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:25px;
}

.stat-card{
    background:white;
    padding:22px;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(15,23,42,0.06);
}

.stat-card h3{
    font-size:13px;
    color:#64748b;
    margin-bottom:10px;
}

.stat-card p{
    font-size:28px;
    font-weight:bold;
}

.total{
    border-left:5px solid #2563eb;
}

.pending{
    border-left:5px solid #f59e0b;
}

.progress{
    border-left:5px solid #8b5cf6;
}

.resolved{
    border-left:5px solid #16a34a;
}


/* SOS SECTION */

.sos-section{
    margin-bottom:25px;
}

.sos-section h2{
    margin-bottom:15px;
}

.sos-cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.sos-card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 3px 12px rgba(15,23,42,0.06);
}

.sos-card h3{
    font-size:13px;
    color:#64748b;
    margin-bottom:10px;
}

.sos-card p{
    font-size:26px;
    font-weight:bold;
}

.sos-total{
    border-left:5px solid #dc2626;
}

.sos-pending{
    border-left:5px solid #f59e0b;
}

.sos-accepted{
    border-left:5px solid #2563eb;
}

.sos-resolved{
    border-left:5px solid #16a34a;
}


/* SOS BUTTON */

.sos-button{
    display:inline-block;
    margin-bottom:25px;
    padding:13px 22px;
    background:#dc2626;
    color:white;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
}

.sos-button:hover{
    background:#b91c1c;
}


/* TABLE */

.table-card{
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 3px 12px rgba(15,23,42,0.06);
    overflow-x:auto;
}

.table-card h2{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

th{
    background:#172554;
    color:white;
    padding:12px;
    text-align:left;
    font-size:13px;
}

td{
    padding:12px;
    border-bottom:1px solid #e2e8f0;
    font-size:13px;
    vertical-align:top;
}

tr:hover{
    background:#f8fafc;
}


/* FORM */

.status-form{
    min-width:180px;
}

.status-form select,
.status-form input{
    width:100%;
    padding:8px;
    margin-bottom:8px;
    border:1px solid #cbd5e1;
    border-radius:6px;
}

.status-form button{
    width:100%;
    padding:9px;
    border:none;
    background:#2563eb;
    color:white;
    border-radius:6px;
    cursor:pointer;
}

.status-form button:hover{
    background:#1d4ed8;
}


/* EMPTY */

.empty{
    text-align:center;
    padding:40px;
    color:#64748b;
}


/* RESPONSIVE */

@media(max-width:1000px){

    .stats,
    .sos-cards{
        grid-template-columns:repeat(2,1fr);
    }

}

@media(max-width:700px){

    .sidebar{
        width:210px;
    }

    .main{
        margin-left:210px;
    }

    .stats,
    .sos-cards{
        grid-template-columns:1fr;
    }

}

</style>

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">

        🏫 Campus<span>Care</span>

    </div>


    <!-- PROFILE -->

    <div class="sidebar-profile">

        <?php if(
            !empty($user['profile_pic']) &&
            file_exists("../uploads/".$user['profile_pic'])
        ){ ?>

            <img
                src="../uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>"
                alt="Profile Picture"
            >

        <?php }else{ ?>

            <div class="sidebar-profile-placeholder">

                <?php
                echo strtoupper(
                    substr($user['fullname'],0,1)
                );
                ?>

            </div>

        <?php } ?>


        <h3>

            <?php
            echo htmlspecialchars($user['fullname']);
            ?>

        </h3>


        <p>

            🛡️ Security

        </p>

    </div>


    <!-- MENU -->

    <div class="menu-title">

        Security Menu

    </div>


    <a href="dashboard.php"
       class="active">

        🏠 Dashboard

    </a>


    <a href="../department/sos_requests.php">

        🚨 SOS Requests

    </a>


    <a href="../department/notices.php">

        📢 Notice Board

    </a>


    <a href="../department/lost_found.php">

        📦 Lost & Found

    </a>


    <a href="../department/feedback.php">

        💬 Student Feedback

    </a>


    <a href="profile.php">

        👤 My Profile

    </a>


    <a href="../logout.php"
       class="logout">

        🚪 Logout

    </a>

</div>



<!-- MAIN -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <h2>

            Security Dashboard

        </h2>


        <div class="topbar-right">

            🛡️ Security Account

        </div>

    </div>



    <!-- CONTENT -->

    <div class="content">


        <!-- WELCOME -->

        <div class="welcome">

            <h1>

                👋 Welcome,
                <?php
                echo htmlspecialchars($user['fullname']);
                ?>

            </h1>


            <p>

                Monitor emergency SOS requests and manage security-related complaints.

            </p>

        </div>



        <!-- SOS BUTTON -->

        <a
            href="../department/sos_requests.php"
            class="sos-button"
        >

            🚨 Open SOS Requests

        </a>



        <!-- SOS STATISTICS -->

        <div class="sos-section">

            <h2>

                🚨 Emergency SOS Overview

            </h2>


            <div class="sos-cards">


                <div class="sos-card sos-total">

                    <h3>
                        Total SOS
                    </h3>

                    <p>
                        <?php echo $sos_total; ?>
                    </p>

                </div>


                <div class="sos-card sos-pending">

                    <h3>
                        Pending
                    </h3>

                    <p>
                        <?php echo $sos_pending; ?>
                    </p>

                </div>


                <div class="sos-card sos-accepted">

                    <h3>
                        Accepted
                    </h3>

                    <p>
                        <?php echo $sos_accepted; ?>
                    </p>

                </div>


                <div class="sos-card sos-resolved">

                    <h3>
                        Resolved
                    </h3>

                    <p>
                        <?php echo $sos_resolved; ?>
                    </p>

                </div>


            </div>

        </div>



        <!-- COMPLAINT STATISTICS -->

        <div class="stats">


            <div class="stat-card total">

                <h3>
                    Total Complaints
                </h3>

                <p>
                    <?php echo $total; ?>
                </p>

            </div>


            <div class="stat-card pending">

                <h3>
                    Pending
                </h3>

                <p>
                    <?php echo $pending; ?>
                </p>

            </div>


            <div class="stat-card progress">

                <h3>
                    In Progress
                </h3>

                <p>
                    <?php echo $progress; ?>
                </p>

            </div>


            <div class="stat-card resolved">

                <h3>
                    Resolved
                </h3>

                <p>
                    <?php echo $resolved; ?>
                </p>

            </div>


        </div>



        <!-- COMPLAINT TABLE -->

        <div class="table-card">

            <h2>

                🛡️ Assigned Security Complaints

            </h2>


            <?php if(mysqli_num_rows($query) > 0){ ?>


            <table>

                <tr>

                    <th>ID</th>

                    <th>Student</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Status</th>

                    <th>Remarks</th>

                    <th>Update Status</th>

                    <th>Date</th>

                </tr>


                <?php while(
                    $row=mysqli_fetch_assoc($query)
                ){ ?>


                <tr>

                    <td>
                        <?php echo htmlspecialchars($row['id']); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($row['fullname']); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($row['title']); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($row['category']); ?>
                    </td>


                    <td>
                        <?php echo htmlspecialchars($row['status']); ?>
                    </td>


                    <td>

                        <?php

                        echo !empty($row['remarks'])
                            ? htmlspecialchars($row['remarks'])
                            : "—";

                        ?>

                    </td>


                    <td>

                        <form
                            action="update_status.php"
                            method="POST"
                            class="status-form"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?php echo $row['id']; ?>"
                            >


                            <select
                                name="status"
                                required
                            >

                                <option value="Pending"
                                    <?php
                                    if($row['status']=="Pending")
                                        echo "selected";
                                    ?>
                                >
                                    Pending
                                </option>


                                <option value="In Progress"
                                    <?php
                                    if($row['status']=="In Progress")
                                        echo "selected";
                                    ?>
                                >
                                    In Progress
                                </option>


                                <option value="Resolved"
                                    <?php
                                    if($row['status']=="Resolved")
                                        echo "selected";
                                    ?>
                                >
                                    Resolved
                                </option>

                            </select>


                            <input
                                type="text"
                                name="remarks"
                                placeholder="Enter Remarks"
                                value="<?php echo htmlspecialchars($row['remarks']); ?>"
                            >


                            <button
                                type="submit"
                                name="update"
                            >

                                Update

                            </button>

                        </form>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $row['created_at']
                        );
                        ?>

                    </td>

                </tr>


                <?php } ?>


            </table>


            <?php }else{ ?>


            <div class="empty">

                <h3>
                    No Security Complaints Assigned
                </h3>

                <p>
                    Currently there are no complaints assigned to Security.
                </p>

            </div>


            <?php } ?>


        </div>


    </div>

</div>


</body>

</html>