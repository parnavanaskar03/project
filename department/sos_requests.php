<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$role = $_SESSION['role'];

/* Student and Admin are not allowed here */
if($role == "student" || $role == "admin"){
    header("Location: ../login.php");
    exit();
}


/* User information */

$user_id = $_SESSION['user_id'];

$user_query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($user_query);


/* Notices */
/*
   Show only Active notices
   which are either for All users
   or specifically for the logged-in user's role.
*/

$query = mysqli_query($conn, "
    SELECT *
    FROM notices
    WHERE status='Active'
    AND (
        target_role='All'
        OR target_role='$role'
    )
    ORDER BY id DESC
");


/* Total notices */

$total_notices = mysqli_num_rows($query);


/* Dashboard link */

$back = "";

switch($role){

    case "teacher":
        $back = "../teacher/dashboard.php";
        break;

    case "hod":
        $back = "../hod/dashboard.php";
        break;

    case "warden":
        $back = "../warden/dashboard.php";
        break;

    case "electrician":
        $back = "../electrician/dashboard.php";
        break;

    case "security":
        $back = "../security/dashboard.php";
        break;

    default:
        $back = "../login.php";
        break;
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Notice Board - Campus Care</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
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
    width:240px;
    height:100vh;
    background:#172554;
    color:white;
    padding:25px 15px;
}

.logo{
    text-align:center;
    font-size:23px;
    font-weight:bold;
    margin-bottom:35px;
}

.logo span{
    color:#60a5fa;
}


.profile{
    text-align:center;
    margin-bottom:30px;
}

.profile-circle{
    width:65px;
    height:65px;
    border-radius:50%;
    background:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    font-size:28px;
    font-weight:bold;
}

.profile h3{
    margin-top:10px;
    font-size:16px;
}

.profile p{
    font-size:12px;
    color:#cbd5e1;
    margin-top:4px;
}


.nav a{
    display:block;
    color:#e2e8f0;
    text-decoration:none;
    padding:12px 15px;
    margin:5px 0;
    border-radius:8px;
    font-size:14px;
}

.nav a:hover{
    background:#1e40af;
    color:white;
}

.nav .active{
    background:#2563eb;
    color:white;
}

.logout{
    margin-top:25px;
    color:#fecaca !important;
}


/* MAIN */

.main{
    margin-left:240px;
    padding:25px;
}


/* TOPBAR */

.topbar{
    background:white;
    padding:18px 22px;
    border-radius:12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.topbar h2{
    font-size:21px;
}

.topbar p{
    font-size:13px;
    color:#64748b;
}


/* WELCOME */

.welcome{
    background:#2563eb;
    color:white;
    padding:25px;
    border-radius:14px;
    margin-bottom:20px;
}

.welcome h2{
    font-size:22px;
    margin-bottom:7px;
}

.welcome p{
    font-size:14px;
    color:#dbeafe;
}


/* STAT */

.stat-card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
    width:250px;
    margin-bottom:20px;
}

.stat-icon{
    font-size:25px;
    margin-bottom:10px;
}

.stat-card h3{
    font-size:25px;
    margin-bottom:4px;
}

.stat-card p{
    color:#64748b;
    font-size:13px;
}


/* SECTION */

.section{
    background:white;
    border-radius:12px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
}

.section-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.section-header h2{
    font-size:18px;
}


/* NOTICE CARDS */

.notice-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
}

.notice-card{
    border:1px solid #e2e8f0;
    border-radius:10px;
    padding:18px;
    background:#f8fafc;
    transition:0.2s;
}

.notice-card:hover{
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transform:translateY(-2px);
}

.notice-icon{
    font-size:25px;
    margin-bottom:10px;
}

.notice-card h3{
    font-size:17px;
    margin-bottom:8px;
    color:#172554;
}

.notice-description{
    font-size:13px;
    color:#475569;
    line-height:1.6;
    margin-bottom:12px;
}

.notice-date{
    font-size:11px;
    color:#64748b;
}


/* TARGET */

.notice-target{
    display:inline-block;
    margin-top:10px;
    padding:5px 9px;
    background:#dbeafe;
    color:#1d4ed8;
    border-radius:15px;
    font-size:10px;
    font-weight:bold;
}


/* EMPTY */

.empty{
    text-align:center;
    padding:45px 20px;
    color:#64748b;
}

.empty-icon{
    font-size:40px;
    margin-bottom:10px;
}


/* BACK BUTTON */

.back-btn{
    display:inline-block;
    background:#e2e8f0;
    color:#1e293b;
    text-decoration:none;
    padding:10px 18px;
    border-radius:7px;
    font-size:13px;
}

.back-btn:hover{
    background:#cbd5e1;
}


/* FOOTER */

.footer{
    text-align:center;
    padding:20px;
    color:#64748b;
    font-size:12px;
}


/* RESPONSIVE */

@media(max-width:900px){

    .sidebar{
        width:200px;
    }

    .main{
        margin-left:200px;
    }

    .notice-grid{
        grid-template-columns:1fr;
    }

}

@media(max-width:650px){

    .sidebar{
        position:relative;
        width:100%;
        height:auto;
    }

    .main{
        margin-left:0;
        padding:15px;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:5px;
    }

    .stat-card{
        width:100%;
    }

}

</style>

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">
        Campus <span>Care</span>
    </div>


    <div class="profile">

        <div class="profile-circle">

            <?php

            echo strtoupper(
                substr($user['fullname'],0,1)
            );

            ?>

        </div>


        <h3>

            <?php

            echo htmlspecialchars(
                $user['fullname']
            );

            ?>

        </h3>


        <p>

            <?php

            echo ucfirst($role);

            ?>

        </p>

    </div>


    <div class="nav">

        <a href="<?php echo $back; ?>">
            🏠 Dashboard
        </a>


        <?php if($role == "teacher"){ ?>

            <a href="sos_requests.php">
                🚨 SOS Requests
            </a>

            <a href="notices.php" class="active">
                📢 Notice Board
            </a>

            <a href="lost_found.php">
                📦 Lost & Found
            </a>

            <a href="feedback.php">
                💬 Student Feedback
            </a>

        <?php } ?>


        <a href="../logout.php" class="logout">
            🚪 Logout
        </a>

    </div>

</div>



<!-- MAIN -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>

            <h2>
                📢 Notice Board
            </h2>

            <p>
                Campus Care Notice Management
            </p>

        </div>


        <div>

            👤

            <?php

            echo ucfirst($role);

            ?>

        </div>

    </div>



    <!-- WELCOME -->

    <div class="welcome">

        <h2>
            Campus Notice Board 📢
        </h2>

        <p>
            View important notices and announcements from the college.
        </p>

    </div>



    <!-- STAT -->

    <div class="stat-card">

        <div class="stat-icon">
            📢
        </div>

        <h3>

            <?php

            echo $total_notices;

            ?>

        </h3>

        <p>
            Available Notices
        </p>

    </div>



    <!-- NOTICE SECTION -->

    <div class="section">

        <div class="section-header">

            <h2>
                📢 Available Notices
            </h2>

        </div>


        <?php

        if($total_notices > 0){

        ?>

        <div class="notice-grid">

            <?php

            while($row = mysqli_fetch_assoc($query)){

            ?>

            <div class="notice-card">

                <div class="notice-icon">
                    📢
                </div>


                <h3>

                    <?php

                    echo htmlspecialchars(
                        $row['title']
                    );

                    ?>

                </h3>


                <div class="notice-description">

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $row['description']
                        )
                    );

                    ?>

                </div>


                <div class="notice-date">

                    🕒 Published:

                    <?php

                    echo htmlspecialchars(
                        $row['created_at']
                    );

                    ?>

                </div>


                <div class="notice-target">

                    👥
                    <?php

                    echo htmlspecialchars(
                        $row['target_role']
                    );

                    ?>

                </div>

            </div>

            <?php

            }

            ?>

        </div>


        <?php

        }else{

        ?>

        <div class="empty">

            <div class="empty-icon">
                📢
            </div>

            <h3>
                No Notices Available
            </h3>

            <p>
                There are currently no notices for your account.
            </p>

        </div>

        <?php

        }

        ?>

    </div>



    <!-- BACK -->

    <div class="section">

        <a
            href="<?php echo $back; ?>"
            class="back-btn"
        >
            ⬅ Back to Dashboard
        </a>

    </div>



    <!-- FOOTER -->

    <div class="footer">

        © <?php echo date("Y"); ?>

        Campus Care |

        Smart Campus Management System

    </div>


</div>


</body>

</html>