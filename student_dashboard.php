<?php

session_start();
include("config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_SESSION['user_id'];

$user = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM users
    WHERE id='$id'
"));

$totalComplaints = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM complaints
    WHERE user_id='$id'
"));

$pendingComplaints = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM complaints
    WHERE user_id='$id'
    AND status='Pending'
"));

$resolvedComplaints = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM complaints
    WHERE user_id='$id'
    AND status='Resolved'
"));

$totalSOS = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM sos_requests
    WHERE user_id='$id'
"));

$totalNotice = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) total
    FROM notices
"));

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Student Dashboard</title>

<style>

/* =========================
   RESET
========================= */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f5f7fb;
    color:#1f2937;
}


/* =========================
   MAIN LAYOUT
========================= */

.dashboard-wrapper{
    display:flex;
    min-height:100vh;
}


/* =========================
   SIDEBAR
========================= */

.sidebar{
    width:260px;
    background:#172554;
    color:white;
    min-height:100vh;
    padding:25px 18px;
    position:fixed;
    left:0;
    top:0;
    bottom:0;
}

.logo{
    text-align:center;
    margin-bottom:30px;
}

.logo-icon{
    width:55px;
    height:55px;
    background:#2563eb;
    border-radius:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:28px;
}

.logo h2{
    margin-top:10px;
    font-size:22px;
}

.logo p{
    font-size:12px;
    color:#bfdbfe;
    margin-top:4px;
}


/* =========================
   PROFILE
========================= */

.profile-box{
    text-align:center;
    padding:18px 10px;
    background:rgba(255,255,255,0.08);
    border-radius:15px;
    margin-bottom:22px;
}

.profile-box img{
    width:75px;
    height:75px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid white;
}

.profile-box h3{
    margin-top:10px;
    font-size:16px;
}

.profile-box p{
    margin-top:4px;
    font-size:13px;
    color:#bfdbfe;
}


/* =========================
   NAVIGATION
========================= */

.sidebar-nav{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.sidebar-nav a{
    text-decoration:none;
    color:#dbeafe;
    padding:12px 14px;
    border-radius:10px;
    font-size:14px;
    transition:0.2s;
}

.sidebar-nav a:hover{
    background:#2563eb;
    color:white;
}

.sidebar-nav .active{
    background:#2563eb;
    color:white;
}


/* =========================
   LOGOUT
========================= */

.logout{
    margin-top:18px;
    border-top:1px solid rgba(255,255,255,0.15);
    padding-top:14px;
    display:flex;
    flex-direction:column;
    gap:6px;
}

.logout a{
    display:block;
    width:100%;
    text-decoration:none;
    color:#fecaca;
    padding:12px 14px;
    border-radius:10px;
    font-size:14px;
}

.logout a:hover{
    background:rgba(239,68,68,0.15);
    color:#fff;
}


/* =========================
   MAIN CONTENT
========================= */

.main-content{
    margin-left:260px;
    width:calc(100% - 260px);
    padding:30px;
}


/* =========================
   TOP BAR
========================= */

.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.topbar h1{
    font-size:27px;
    color:#172554;
}

.topbar p{
    margin-top:5px;
    color:#64748b;
    font-size:14px;
}

.top-profile{
    background:white;
    padding:10px 16px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
    font-size:14px;
}


/* =========================
   WELCOME BOX
========================= */

.welcome-box{
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    color:white;
    padding:28px;
    border-radius:18px;
    margin-bottom:25px;
    box-shadow:0 8px 25px rgba(37,99,235,0.20);
}

.welcome-box h2{
    font-size:24px;
    margin-bottom:8px;
}

.welcome-box p{
    color:#dbeafe;
    font-size:14px;
}


/* =========================
   STATISTICS
========================= */

.stats-grid{
    display:grid;
    grid-template-columns:repeat(5,1fr);
    gap:18px;
    margin-bottom:25px;
}

.stat-card{
    background:white;
    border-radius:16px;
    padding:20px;
    box-shadow:0 4px 18px rgba(0,0,0,0.06);
    border:1px solid #e5e7eb;
}

.stat-icon{
    width:42px;
    height:42px;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
    background:#eff6ff;
    margin-bottom:14px;
}

.stat-card h4{
    color:#64748b;
    font-size:13px;
    font-weight:normal;
}

.stat-card h2{
    margin-top:6px;
    font-size:28px;
    color:#172554;
}


/* =========================
   STUDENT INFORMATION
========================= */

.student-info-card{
    background:white;
    border-radius:16px;
    padding:24px;
    box-shadow:0 4px 18px rgba(0,0,0,0.06);
    border:1px solid #e5e7eb;
    margin-bottom:22px;
}

.student-info-card h2{
    font-size:19px;
    color:#172554;
    margin-bottom:18px;
}

.info-table{
    width:100%;
    border-collapse:collapse;
}

.info-table tr{
    border-bottom:1px solid #eef2f7;
}

.info-table tr:last-child{
    border-bottom:none;
}

.info-table td{
    padding:13px 5px;
    font-size:14px;
}

.info-label{
    color:#64748b;
    width:30%;
}

.info-value{
    color:#1e293b;
    font-weight:600;
}


/* =========================
   BOTTOM SECTION
========================= */

.bottom-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:22px;
}


/* =========================
   DASHBOARD CARD
========================= */

.dashboard-card{
    background:white;
    border-radius:16px;
    padding:24px;
    box-shadow:0 4px 18px rgba(0,0,0,0.06);
    border:1px solid #e5e7eb;
}

.dashboard-card h2{
    font-size:19px;
    color:#172554;
    margin-bottom:18px;
}


/* =========================
   NOTICE
========================= */

.notice-box{
    display:flex;
    align-items:center;
    gap:15px;
    padding:15px;
    background:#f8fafc;
    border-radius:12px;
}

.notice-icon{
    width:45px;
    height:45px;
    background:#eff6ff;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
}

.notice-box h3{
    font-size:15px;
}

.notice-box p{
    font-size:13px;
    color:#64748b;
    margin-top:4px;
}


/* =========================
   BUTTON
========================= */

.view-btn{
    display:block;
    text-align:center;
    text-decoration:none;
    padding:12px 15px;
    border-radius:10px;
    background:#eff6ff;
    border:1px solid #dbeafe;
    color:#1d4ed8;
    font-size:14px;
    transition:0.2s;
}

.view-btn:hover{
    background:#dbeafe;
}


/* =========================
   SOS INFORMATION
========================= */

.sos-info{
    background:#fff7ed;
    border:1px solid #fed7aa;
}

.sos-info h2{
    color:#9a3412;
}

.sos-info p{
    font-size:13px;
    line-height:1.6;
    color:#7c2d12;
}

.sos-btn{
    display:block;
    text-align:center;
    text-decoration:none;
    padding:12px 15px;
    border-radius:10px;
    background:#dc2626;
    color:white;
    font-size:14px;
    transition:0.2s;
}

.sos-btn:hover{
    background:#b91c1c;
}


/* =========================
   FOOTER
========================= */

.footer{
    text-align:center;
    padding:25px 10px 10px;
    color:#94a3b8;
    font-size:13px;
}


/* =========================
   RESPONSIVE
========================= */

@media(max-width:1100px){

    .stats-grid{
        grid-template-columns:repeat(3,1fr);
    }

    .bottom-grid{
        grid-template-columns:1fr;
    }

}


@media(max-width:800px){

    .sidebar{
        position:relative;
        width:100%;
        min-height:auto;
    }

    .dashboard-wrapper{
        display:block;
    }

    .main-content{
        margin-left:0;
        width:100%;
        padding:20px;
    }

    .sidebar-nav{
        display:grid;
        grid-template-columns:repeat(2,1fr);
    }

    .stats-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .info-label{
        width:40%;
    }

}


@media(max-width:500px){

    .stats-grid{
        grid-template-columns:1fr;
    }

    .topbar{
        display:block;
    }

    .top-profile{
        margin-top:12px;
        display:inline-block;
    }

    .student-info-card,
    .dashboard-card{
        padding:18px;
    }

    .info-table td{
        font-size:13px;
    }

}

</style>

</head>


<body>

<div class="dashboard-wrapper">


<!-- =========================
     SIDEBAR
========================= -->

<aside class="sidebar">

    <div class="logo">

        <div class="logo-icon">
            🏫
        </div>

        <h2>Campus Care</h2>

        <p>Smart Campus Management</p>

    </div>


    <!-- PROFILE -->

    <div class="profile-box">

        <?php if(!empty($user['profile_pic'])){ ?>

            <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>">

        <?php }else{ ?>

            <img src="assets/images/default.png">

        <?php } ?>

        <h3>
            <?php echo htmlspecialchars($user['fullname']); ?>
        </h3>

        <p>Student</p>

    </div>


    <!-- NAVIGATION -->

    <nav class="sidebar-nav">

        <a href="dashboard.php" class="active">
            🏠 &nbsp; Dashboard
        </a>

        <a href="student/complaint.php">
            ➕ &nbsp; New Complaint
        </a>

        <a href="student/my_complaints.php">
            📋 &nbsp; My Complaints
        </a>

        <a href="student/notices.php">
            📢 &nbsp; Notice Board
        </a>

        <a href="student/lost_found.php">
            📦 &nbsp; Lost & Found
        </a>

        <a href="student/my_lost_found.php">
            📂 &nbsp; My Lost Found
        </a>

        <a href="student/feedback.php">
            ⭐ &nbsp; Feedback
        </a>

        <a href="student/profile.php">
            👤 &nbsp; My Profile
        </a>

        <a href="student/my_sos.php">
            📍 &nbsp; My SOS Status
        </a>


        <div class="logout">

            <a href="student/sos.php">
                🚨 &nbsp; Emergency SOS
            </a>

            <a href="logout.php">
                🚪 &nbsp; Logout
            </a>

        </div>

    </nav>

</aside>



<!-- =========================
     MAIN CONTENT
========================= -->

<main class="main-content">


    <!-- TOP BAR -->

    <div class="topbar">

        <div>

            <h1>Student Dashboard</h1>

            <p>
                Manage your campus services from one place.
            </p>

        </div>

        <div class="top-profile">

            👤
            <?php echo htmlspecialchars($user['fullname']); ?>

        </div>

    </div>



    <!-- WELCOME -->

    <div class="welcome-box">

        <h2>
            Welcome, <?php echo htmlspecialchars($user['fullname']); ?> 👋
        </h2>

        <p>
            Campus Care helps you report problems, stay updated,
            request emergency help and access important campus services.
        </p>

    </div>



    <!-- =========================
         STATISTICS
    ========================= -->

    <div class="stats-grid">


        <div class="stat-card">

            <div class="stat-icon">
                📋
            </div>

            <h4>Total Complaints</h4>

            <h2>
                <?php echo $totalComplaints['total']; ?>
            </h2>

        </div>



        <div class="stat-card">

            <div class="stat-icon">
                ⏳
            </div>

            <h4>Pending</h4>

            <h2>
                <?php echo $pendingComplaints['total']; ?>
            </h2>

        </div>



        <div class="stat-card">

            <div class="stat-icon">
                ✅
            </div>

            <h4>Resolved</h4>

            <h2>
                <?php echo $resolvedComplaints['total']; ?>
            </h2>

        </div>



        <div class="stat-card">

            <div class="stat-icon">
                🚨
            </div>

            <h4>SOS Requests</h4>

            <h2>
                <?php echo $totalSOS['total']; ?>
            </h2>

        </div>



        <div class="stat-card">

            <div class="stat-icon">
                📢
            </div>

            <h4>Notices</h4>

            <h2>
                <?php echo $totalNotice['total']; ?>
            </h2>

        </div>


    </div>



    <!-- =========================
         STUDENT INFORMATION
    ========================= -->

    <div class="student-info-card">

        <h2>👤 Student Information</h2>

        <table class="info-table">

            <tr>

                <td class="info-label">
                    Name
                </td>

                <td class="info-value">
                    <?php echo htmlspecialchars($user['fullname']); ?>
                </td>

            </tr>


            <tr>

                <td class="info-label">
                    Student ID
                </td>

                <td class="info-value">
                    <?php echo htmlspecialchars($user['student_id']); ?>
                </td>

            </tr>


            <tr>

                <td class="info-label">
                    Email
                </td>

                <td class="info-value">
                    <?php echo htmlspecialchars($user['email']); ?>
                </td>

            </tr>


            <tr>

                <td class="info-label">
                    Phone
                </td>

                <td class="info-value">
                    <?php echo htmlspecialchars($user['phone']); ?>
                </td>

            </tr>


            <tr>

                <td class="info-label">
                    Department
                </td>

                <td class="info-value">
                    <?php echo htmlspecialchars($user['department']); ?>
                </td>

            </tr>

        </table>

    </div>



    <!-- =========================
         BOTTOM GRID
    ========================= -->

    <div class="bottom-grid">


        <!-- NOTICE -->

        <div class="dashboard-card">

            <h2>📢 Notice Board</h2>

            <div class="notice-box">

                <div class="notice-icon">
                    📢
                </div>

                <div>

                    <h3>
                        Stay Updated
                    </h3>

                    <p>
                        Check the notice board regularly
                        for important campus announcements.
                    </p>

                </div>

            </div>

            <br>

            <a href="student/notices.php"
               class="view-btn">

                View Notice Board →

            </a>

        </div>



        <!-- SOS -->

        <div class="dashboard-card sos-info">

            <h2>🚨 Emergency SOS</h2>

            <p>

                Campus Care provides an emergency SOS
                facility for students. Use it when you
                need urgent assistance inside the campus.

            </p>

            <br>

            <a href="student/sos.php"
               class="sos-btn">

                🚨 Send Emergency SOS

            </a>

        </div>


    </div>



    <!-- =========================
         FOOTER
    ========================= -->

    <div class="footer">

        <strong>Campus Care Management System</strong>

        <br><br>

        The Neotia University

        <br>

        © 2026 Campus Care. All Rights Reserved.

    </div>


</main>

</div>

</body>

</html>