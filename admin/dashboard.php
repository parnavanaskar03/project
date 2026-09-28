<?php

session_start();
include("../config/db.php");


/* =========================
   ADMIN LOGIN CHECK
========================= */

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit();
}


/* =========================
   ADMIN DETAILS
========================= */

$admin = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM users WHERE id='".$_SESSION['user_id']."'"
    )
);


/* =========================
   DASHBOARD COUNTS
========================= */

$totalComplaints = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM complaints"
    )
)['total'];

$pendingComplaints = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM complaints
         WHERE status='Pending'"
    )
)['total'];

$progressComplaints = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM complaints
         WHERE status='In Progress'"
    )
)['total'];

$resolvedComplaints = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM complaints
         WHERE status='Resolved'"
    )
)['total'];

$totalSOS = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM sos_requests"
    )
)['total'];

$totalLost = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM lost_found"
    )
)['total'];

$totalFeedback = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM feedback"
    )
)['total'];


/* =========================
   RECENT COMPLAINTS
========================= */

$query = mysqli_query(
    $conn,
    "SELECT complaints.*, users.fullname
     FROM complaints
     INNER JOIN users
     ON complaints.user_id = users.id
     ORDER BY complaints.id DESC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Campus Care - Admin Dashboard</title>


<style>

/* =========================
   BASIC
========================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f7fb;
    color:#172554;
}


/* =========================
   SIDEBAR
========================= */

.sidebar{

    width:260px;

    background:#172554;

    color:white;

    min-height:100vh;

    padding:18px 10px;

    position:fixed;

    left:0;
    top:0;
    bottom:0;

    overflow-y:auto;
}


/* LOGO */

.logo{

    text-align:center;

    margin-bottom:20px;

    padding:5px;
}

.logo-icon{

    width:52px;
    height:52px;

    background:#2563eb;

    border-radius:14px;

    display:flex;

    align-items:center;
    justify-content:center;

    margin:auto;

    font-size:27px;
}

.logo h2{

    margin-top:9px;

    font-size:20px;
}

.logo p{

    font-size:10px;

    color:#bfdbfe;

    margin-top:3px;
}


/* PROFILE */

.profile-box{

    text-align:center;

    padding:14px 8px;

    background:rgba(255,255,255,0.08);

    border-radius:13px;

    margin-bottom:15px;
}

.profile-box img{

    width:70px;
    height:70px;

    border-radius:50%;

    object-fit:cover;

    border:3px solid white;
}

.profile-box h3{

    margin-top:8px;

    font-size:14px;
}

.profile-box p{

    margin-top:3px;

    font-size:11px;

    color:#bfdbfe;
}


/* NAVIGATION */

.sidebar-nav{

    display:flex;

    flex-direction:column;

    gap:3px;
}

.sidebar-nav a{

    text-decoration:none;

    color:#dbeafe;

    padding:10px 12px;

    border-radius:9px;

    font-size:12px;

    display:block;

    transition:.2s;
}

.sidebar-nav a:hover{

    background:#2563eb;

    color:white;
}

.sidebar-nav a.active{

    background:#2563eb;

    color:white;
}


/* LOGOUT */

.logout{

    margin-top:12px;

    border-top:1px solid rgba(255,255,255,.15);

    padding-top:10px;
}

.logout a{

    display:block;

    text-decoration:none;

    color:#fecaca;

    padding:10px 12px;

    border-radius:9px;

    font-size:12px;
}

.logout a:hover{

    background:rgba(239,68,68,.15);

    color:white;
}


/* =========================
   MAIN CONTENT
========================= */

.main-content{

    margin-left:260px;

    width:calc(100% - 260px);

    padding:22px;
}


/* TOP HEADER */

.top-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:20px;
}

.top-header h1{

    font-size:24px;

    color:#172554;
}

.top-header p{

    color:#64748b;

    font-size:11px;

    margin-top:4px;
}

.admin-name{

    background:white;

    padding:9px 14px;

    border-radius:10px;

    box-shadow:0 3px 12px rgba(0,0,0,.06);

    font-size:11px;

    color:#334155;
}


/* =========================
   WELCOME
========================= */

.welcome{

    background:#2563eb;

    color:white;

    border-radius:14px;

    padding:20px;

    margin-bottom:17px;

    box-shadow:0 5px 15px rgba(37,99,235,.20);
}

.welcome h2{

    font-size:19px;

    margin-bottom:5px;
}

.welcome p{

    font-size:11px;

    color:#dbeafe;
}


/* =========================
   STAT CARDS
========================= */

.stats{

    display:grid;

    grid-template-columns:
    repeat(5,1fr);

    gap:12px;

    margin-bottom:18px;
}

.stat-card{

    background:white;

    border-radius:13px;

    padding:16px;

    box-shadow:0 4px 14px rgba(0,0,0,.06);

    min-height:95px;
}

.stat-icon{

    width:32px;
    height:32px;

    background:#eff6ff;

    border-radius:8px;

    display:flex;

    align-items:center;
    justify-content:center;

    font-size:16px;

    margin-bottom:10px;
}

.stat-card small{

    display:block;

    color:#64748b;

    font-size:10px;

    margin-bottom:4px;
}

.stat-card h2{

    color:#172554;

    font-size:22px;
}


/* =========================
   OVERVIEW
========================= */

.overview-grid{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:16px;

    margin-bottom:18px;
}

.section-box{

    background:white;

    border-radius:14px;

    padding:18px;

    box-shadow:0 4px 14px rgba(0,0,0,.06);
}

.section-title{

    font-size:15px;

    color:#172554;

    margin-bottom:14px;
}


/* OVERVIEW ITEMS */

.overview-list{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:10px;
}

.overview-item{

    display:flex;

    justify-content:space-between;

    align-items:center;

    background:#f8fafc;

    padding:12px;

    border-radius:9px;

    border:1px solid #eef2f7;
}

.overview-item span{

    font-size:10px;

    color:#475569;
}

.overview-item strong{

    font-size:15px;

    color:#172554;
}


/* SYSTEM STATUS */

.system-status{

    display:flex;

    flex-direction:column;

    gap:11px;
}

.system-item{

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:11px 12px;

    background:#f8fafc;

    border-radius:9px;
}

.system-item-left{

    display:flex;

    align-items:center;

    gap:9px;
}

.system-icon{

    width:28px;

    height:28px;

    border-radius:7px;

    background:#eff6ff;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:14px;
}

.system-item span{

    font-size:10px;

    color:#475569;
}

.online{

    font-size:9px;

    color:#15803d;

    background:#dcfce7;

    padding:4px 8px;

    border-radius:20px;

    font-weight:bold;
}


/* =========================
   RECENT COMPLAINT
========================= */

.recent-section{

    background:white;

    border-radius:14px;

    padding:18px;

    box-shadow:0 4px 14px rgba(0,0,0,.06);

    margin-bottom:18px;
}

.recent-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:14px;
}

.recent-header h2{

    font-size:16px;

    color:#172554;
}

.recent-header p{

    font-size:10px;

    color:#64748b;

    margin-top:4px;
}

.view-all{

    text-decoration:none;

    background:#eff6ff;

    color:#2563eb;

    padding:8px 12px;

    border-radius:7px;

    font-size:9px;

    font-weight:bold;
}

.view-all:hover{

    background:#dbeafe;
}


/* COMPLAINT LIST */

.complaint-list{

    display:flex;

    flex-direction:column;

    gap:9px;
}

.complaint-item{

    display:grid;

    grid-template-columns:45px 1.3fr 1fr 120px 150px;

    align-items:center;

    gap:10px;

    padding:12px;

    border:1px solid #eef2f7;

    border-radius:9px;

    background:#fafcff;
}

.complaint-id{

    font-size:10px;

    color:#64748b;

    font-weight:bold;
}

.student-name{

    font-size:10px;

    font-weight:bold;

    color:#172554;
}

.complaint-title{

    font-size:10px;

    color:#475569;
}

.complaint-category{

    font-size:9px;

    color:#64748b;
}

.date{

    font-size:9px;

    color:#64748b;
}


/* STATUS BADGES */

.status-badge{

    display:inline-block;

    padding:5px 8px;

    border-radius:20px;

    font-size:8px;

    font-weight:bold;

    text-align:center;
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

    color:#166534;
}


/* EMPTY */

.empty-state{

    text-align:center;

    padding:30px;

    color:#94a3b8;

    font-size:11px;
}


/* =========================
   COMPLAINT MANAGEMENT
========================= */

.complaint-section{

    background:white;

    border-radius:14px;

    padding:18px;

    box-shadow:0 4px 14px rgba(0,0,0,.06);
}

.complaint-header{

    margin-bottom:15px;
}

.complaint-header h2{

    font-size:16px;

    color:#172554;
}

.complaint-header p{

    font-size:10px;

    color:#64748b;

    margin-top:4px;
}


/* TABLE */

.table-wrapper{

    width:100%;

    overflow-x:auto;
}

table{

    width:100%;

    border-collapse:collapse;

    min-width:1050px;
}

th{

    background:#f8fafc;

    color:#475569;

    text-align:left;

    font-size:10px;

    padding:11px 9px;

    border-bottom:1px solid #e2e8f0;
}

td{

    padding:10px 9px;

    font-size:10px;

    color:#334155;

    border-bottom:1px solid #eef2f7;

    vertical-align:top;
}

tr:hover{

    background:#fafcff;
}


/* UPDATE FORMS */

.update-form{

    display:flex;

    flex-direction:column;

    gap:6px;
}

.update-form select{

    padding:7px;

    border:1px solid #dbe3ef;

    border-radius:6px;

    font-size:9px;

    outline:none;

    background:white;
}

.update-form select:focus{

    border-color:#2563eb;
}

.update-btn{

    border:none;

    background:#2563eb;

    color:white;

    padding:7px 10px;

    border-radius:6px;

    font-size:9px;

    cursor:pointer;

    font-weight:bold;
}

.update-btn:hover{

    background:#1d4ed8;
}

.assign-btn{

    background:#172554;
}

.assign-btn:hover{

    background:#1e3a8a;
}


/* =========================
   FOOTER
========================= */

.footer{

    text-align:center;

    color:#94a3b8;

    font-size:9px;

    padding:18px 0 5px;
}


/* =========================
   RESPONSIVE
========================= */

@media(max-width:1100px){

    .stats{

        grid-template-columns:
        repeat(3,1fr);
    }

    .overview-grid{

        grid-template-columns:1fr;
    }

    .complaint-item{

        grid-template-columns:
        40px 1fr 1fr;
    }

}

@media(max-width:750px){

    .sidebar{

        width:220px;
    }

    .main-content{

        margin-left:220px;

        width:calc(100% - 220px);

        padding:15px;
    }

    .stats{

        grid-template-columns:
        repeat(2,1fr);
    }

    .overview-list{

        grid-template-columns:1fr;
    }

    .complaint-item{

        grid-template-columns:1fr 1fr;
    }

}

@media(max-width:550px){

    .sidebar{

        position:relative;

        width:100%;

        min-height:auto;
    }

    .main-content{

        margin-left:0;

        width:100%;
    }

    .top-header{

        flex-direction:column;

        align-items:flex-start;

        gap:10px;
    }

    .stats{

        grid-template-columns:1fr;
    }

    .complaint-item{

        grid-template-columns:1fr;
    }

}

</style>

</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">


    <!-- LOGO -->

    <div class="logo">

        <div class="logo-icon">
            🏫
        </div>

        <h2>Campus Care</h2>

        <p>Smart Campus Management</p>

    </div>


    <!-- PROFILE -->

    <div class="profile-box">

        <?php

        if(!empty($admin['profile_pic'])){

            echo "<img
            src='../uploads/".$admin['profile_pic']."'>";

        }else{

            echo "<img
            src='../assets/images/user.png'>";

        }

        ?>

        <h3>
            <?php echo htmlspecialchars($admin['fullname']); ?>
        </h3>

        <p>Administrator</p>

    </div>


    <!-- NAVIGATION -->

    <div class="sidebar-nav">

        <a href="dashboard.php" class="active">
            🏠 &nbsp; Dashboard
        </a>

        <a href="add_notice.php">
            📢 &nbsp; Publish Notice
        </a>

        <a href="sos_requests.php">
            🚨 &nbsp; SOS Requests
        </a>

        <a href="lost_found.php">
            📦 &nbsp; Lost & Found
        </a>

        <a href="feedback.php">
            ⭐ &nbsp; Feedback
        </a>

        <a href="manage_users.php">
            👥 &nbsp; User Management
        </a>

        <a href="profile.php">
            👤 &nbsp; My Profile
        </a>

    </div>


    <!-- LOGOUT -->

    <div class="logout">

        <a href="../logout.php">
            🚪 &nbsp; Logout
        </a>

    </div>


</div>


<!-- =========================
     MAIN
========================= -->

<div class="main-content">


    <!-- TOP HEADER -->

    <div class="top-header">

        <div>

            <h1>Admin Dashboard</h1>

            <p>
                Manage your campus services from one place.
            </p>

        </div>


        <div class="admin-name">

            👤
            <?php echo htmlspecialchars($admin['fullname']); ?>

        </div>

    </div>


    <!-- WELCOME -->

    <div class="welcome">

        <h2>
            Welcome, <?php echo htmlspecialchars($admin['fullname']); ?> 👋
        </h2>

        <p>
            Monitor complaints, emergency SOS requests,
            notices, lost & found items and student feedback.
        </p>

    </div>


    <!-- =========================
         STAT CARDS
    ========================= -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-icon">
                📋
            </div>

            <small>Total Complaints</small>

            <h2>
                <?php echo $totalComplaints; ?>
            </h2>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ⏳
            </div>

            <small>Pending</small>

            <h2>
                <?php echo $pendingComplaints; ?>
            </h2>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                🔄
            </div>

            <small>In Progress</small>

            <h2>
                <?php echo $progressComplaints; ?>
            </h2>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ✅
            </div>

            <small>Resolved</small>

            <h2>
                <?php echo $resolvedComplaints; ?>
            </h2>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                🚨
            </div>

            <small>SOS Requests</small>

            <h2>
                <?php echo $totalSOS; ?>
            </h2>

        </div>


    </div>


    <!-- =========================
         OVERVIEW
    ========================= -->

    <div class="overview-grid">


        <!-- CAMPUS OVERVIEW -->

        <div class="section-box">

            <div class="section-title">
                📊 Campus Overview
            </div>


            <div class="overview-list">


                <div class="overview-item">

                    <span>📦 Lost & Found</span>

                    <strong>
                        <?php echo $totalLost; ?>
                    </strong>

                </div>


                <div class="overview-item">

                    <span>⭐ Feedback</span>

                    <strong>
                        <?php echo $totalFeedback; ?>
                    </strong>

                </div>


                <div class="overview-item">

                    <span>🚨 SOS Requests</span>

                    <strong>
                        <?php echo $totalSOS; ?>
                    </strong>

                </div>


                <div class="overview-item">

                    <span>📋 Complaints</span>

                    <strong>
                        <?php echo $totalComplaints; ?>
                    </strong>

                </div>


            </div>

        </div>


        <!-- SYSTEM STATUS -->

        <div class="section-box">

            <div class="section-title">
                ⚙️ System Status
            </div>


            <div class="system-status">


                <div class="system-item">

                    <div class="system-item-left">

                        <div class="system-icon">
                            🗄️
                        </div>

                        <span>Database</span>

                    </div>

                    <span class="online">
                        ● Online
                    </span>

                </div>


                <div class="system-item">

                    <div class="system-item-left">

                        <div class="system-icon">
                            🔐
                        </div>

                        <span>Role Access</span>

                    </div>

                    <span class="online">
                        ● Active
                    </span>

                </div>


                <div class="system-item">

                    <div class="system-item-left">

                        <div class="system-icon">
                            🌐
                        </div>

                        <span>Campus Care</span>

                    </div>

                    <span class="online">
                        ● Running
                    </span>

                </div>


            </div>

        </div>


    </div>


    <!-- =========================
         RECENT COMPLAINTS
    ========================= -->

    <div class="recent-section">


        <div class="recent-header">

            <div>

                <h2>
                    📌 Recent Complaint Activity
                </h2>

                <p>
                    Latest complaints submitted by students.
                </p>

            </div>

        </div>


        <div class="complaint-list">


            <?php

            if(mysqli_num_rows($query) > 0){

                while($recent=mysqli_fetch_assoc($query)){

            ?>


            <div class="complaint-item">


                <div class="complaint-id">

                    #<?php echo $recent['id']; ?>

                </div>


                <div>

                    <div class="student-name">

                        <?php
                        echo htmlspecialchars(
                            $recent['fullname']
                        );
                        ?>

                    </div>

                    <div class="complaint-title">

                        <?php
                        echo htmlspecialchars(
                            $recent['title']
                        );
                        ?>

                    </div>

                </div>


                <div class="complaint-category">

                    <?php
                    echo htmlspecialchars(
                        $recent['category']
                    );
                    ?>

                </div>


                <div>

                    <?php

                    if($recent['status']=="Pending"){

                        echo "<span class='status-badge status-pending'>
                        Pending
                        </span>";

                    }
                    elseif($recent['status']=="In Progress"){

                        echo "<span class='status-badge status-progress'>
                        In Progress
                        </span>";

                    }
                    elseif($recent['status']=="Resolved"){

                        echo "<span class='status-badge status-resolved'>
                        Resolved
                        </span>";

                    }
                    else{

                        echo "<span class='status-badge status-pending'>"
                        .htmlspecialchars($recent['status']).
                        "</span>";

                    }

                    ?>

                </div>


                <div class="date">

                    <?php echo $recent['created_at']; ?>

                </div>


            </div>


            <?php

                }

            }else{

            ?>

                <div class="empty-state">

                    📋 No complaints available yet.

                </div>

            <?php

            }

            ?>


        </div>

    </div>


    <!-- =========================
         COMPLAINT MANAGEMENT
    ========================= -->

    <div class="complaint-section">


        <div class="complaint-header">

            <h2>
                📋 Complaint Management
            </h2>

            <p>
                Review, assign and update student complaints.
            </p>

        </div>


        <div class="table-wrapper">


            <table>


                <tr>

                    <th>ID</th>

                    <th>Student</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Status</th>

                    <th>Assigned To</th>

                    <th>Change Status</th>

                    <th>Assign Complaint</th>

                    <th>Date</th>

                </tr>


                <?php

                /* Re-fetch all complaints */

                $allComplaints = mysqli_query(
                    $conn,
                    "SELECT complaints.*, users.fullname
                     FROM complaints
                     INNER JOIN users
                     ON complaints.user_id = users.id
                     ORDER BY complaints.id DESC"
                );


                while($row=mysqli_fetch_assoc($allComplaints)){

                ?>


                <tr>


                    <td>

                        #<?php echo $row['id']; ?>

                    </td>


                    <td>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $row['fullname']
                            );
                            ?>
                        </strong>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $row['title']
                        );
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $row['category']
                        );
                        ?>

                    </td>


                    <td>

                        <?php

                        if($row['status']=="Pending"){

                            echo "<span class='status-badge status-pending'>
                            Pending
                            </span>";

                        }
                        elseif($row['status']=="In Progress"){

                            echo "<span class='status-badge status-progress'>
                            In Progress
                            </span>";

                        }
                        elseif($row['status']=="Resolved"){

                            echo "<span class='status-badge status-resolved'>
                            Resolved
                            </span>";

                        }
                        else{

                            echo "<span class='status-badge status-pending'>"
                            .htmlspecialchars($row['status']).
                            "</span>";

                        }

                        ?>

                    </td>


                    <td>

                        <?php

                        if(!empty($row['assigned_to'])){

                            echo ucfirst(
                                htmlspecialchars(
                                    $row['assigned_to']
                                )
                            );

                        }else{

                            echo "Not Assigned";

                        }

                        ?>

                    </td>


                    <!-- CHANGE STATUS -->

                    <td>

                        <form
                        action="update_status.php"
                        method="POST"
                        class="update-form">


                            <input
                            type="hidden"
                            name="id"
                            value="<?php echo $row['id']; ?>">


                            <select name="status">

                                <option
                                value="Pending"
                                <?php
                                if($row['status']=="Pending")
                                    echo "selected";
                                ?>>
                                    Pending
                                </option>


                                <option
                                value="In Progress"
                                <?php
                                if($row['status']=="In Progress")
                                    echo "selected";
                                ?>>
                                    In Progress
                                </option>


                                <option
                                value="Resolved"
                                <?php
                                if($row['status']=="Resolved")
                                    echo "selected";
                                ?>>
                                    Resolved
                                </option>

                            </select>


                            <button
                            type="submit"
                            name="update"
                            class="update-btn">

                                Update

                            </button>


                        </form>

                    </td>


                    <!-- ASSIGN -->

                    <td>

                        <form
                        action="assign_complaint.php"
                        method="POST"
                        class="update-form">


                            <input
                            type="hidden"
                            name="id"
                            value="<?php echo $row['id']; ?>">


                            <select name="assigned_to">


                                <option
                                value="teacher"
                                <?php
                                if($row['assigned_to']=="teacher")
                                    echo "selected";
                                ?>>
                                    Teacher
                                </option>


                                <option
                                value="hod"
                                <?php
                                if($row['assigned_to']=="hod")
                                    echo "selected";
                                ?>>
                                    HOD
                                </option>


                                <option
                                value="warden"
                                <?php
                                if($row['assigned_to']=="warden")
                                    echo "selected";
                                ?>>
                                    Warden
                                </option>


                                <option
                                value="electrician"
                                <?php
                                if($row['assigned_to']=="electrician")
                                    echo "selected";
                                ?>>
                                    Electrician
                                </option>


                                <option
                                value="security"
                                <?php
                                if($row['assigned_to']=="security")
                                    echo "selected";
                                ?>>
                                    Security
                                </option>


                            </select>


                            <button
                            type="submit"
                            name="assign"
                            class="update-btn assign-btn">

                                Assign

                            </button>


                        </form>

                    </td>


                    <!-- DATE -->

                    <td>

                        <?php echo $row['created_at']; ?>

                    </td>


                </tr>


                <?php } ?>


            </table>


        </div>

    </div>


    <!-- FOOTER -->

    <div class="footer">

        Campus Care Management System<br>

        The Neotia University<br>

        © 2026 Campus Care. All Rights Reserved.

    </div>


</div>


</body>

</html>