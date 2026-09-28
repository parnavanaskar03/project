<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$role = $_SESSION['role'];

/* Student and Admin are not allowed */
if($role == "student" || $role == "admin"){
    header("Location: ../login.php");
    exit();
}


/* User information */
$user_id = $_SESSION['user_id'];

$user_query = mysqli_query($conn, "
    SELECT * FROM users
    WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($user_query);


/* Feedback */
$query = mysqli_query($conn, "
    SELECT feedback.*, users.fullname
    FROM feedback
    INNER JOIN users
    ON feedback.user_id = users.id
    ORDER BY feedback.id DESC
");


/* Total feedback */
$total_feedback = mysqli_num_rows($query);


/* Average rating */
$rating_query = mysqli_query($conn, "
    SELECT AVG(rating) AS avg_rating
    FROM feedback
");

$rating_data = mysqli_fetch_assoc($rating_query);

$average_rating = round(
    $rating_data['avg_rating'] ?? 0,
    1
);


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

<title>Student Feedback - Campus Care</title>

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


/* STATS */

.stats{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
    margin-bottom:20px;
    max-width:550px;
}

.stat-card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
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


/* FEEDBACK GRID */

.feedback-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:18px;
}

.feedback-card{
    border:1px solid #e2e8f0;
    border-radius:10px;
    padding:18px;
    background:#f8fafc;
    transition:0.2s;
}

.feedback-card:hover{
    transform:translateY(-2px);
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}


/* STUDENT */

.student{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:12px;
}

.student-icon{
    width:38px;
    height:38px;
    border-radius:50%;
    background:#dbeafe;
    color:#1d4ed8;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}

.student-name{
    font-size:14px;
    font-weight:bold;
}


/* RATING */

.rating{
    color:#f59e0b;
    font-size:18px;
    letter-spacing:2px;
    margin-bottom:12px;
}

.rating-number{
    color:#64748b;
    font-size:12px;
    margin-left:6px;
}


/* MESSAGE */

.message{
    background:white;
    border-radius:8px;
    padding:12px;
    font-size:13px;
    color:#475569;
    line-height:1.6;
    margin-bottom:12px;
}


/* DATE */

.date{
    font-size:11px;
    color:#64748b;
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


/* BACK */

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

    .feedback-grid{
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

    .stats{
        grid-template-columns:1fr;
        max-width:none;
    }

    .topbar{
        flex-direction:column;
        align-items:flex-start;
        gap:5px;
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
            echo htmlspecialchars($user['fullname']);
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

            <a href="notices.php">
                📢 Notice Board
            </a>

            <a href="lost_found.php">
                📦 Lost & Found
            </a>

            <a href="feedback.php" class="active">
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
                💬 Student Feedback
            </h2>

            <p>
                Campus Care Feedback Management
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
            Student Feedback 💬
        </h2>

        <p>
            Review feedback and ratings submitted by students.
        </p>

    </div>



    <!-- STATS -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-icon">
                💬
            </div>

            <h3>
                <?php echo $total_feedback; ?>
            </h3>

            <p>
                Total Feedback
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ⭐
            </div>

            <h3>
                <?php echo $average_rating; ?>/5
            </h3>

            <p>
                Average Rating
            </p>

        </div>

    </div>



    <!-- FEEDBACK -->

    <div class="section">

        <div class="section-header">

            <h2>
                💬 Student Reviews
            </h2>

        </div>


        <?php

        if($total_feedback > 0){

        ?>

        <div class="feedback-grid">

            <?php

            while($row = mysqli_fetch_assoc($query)){

                $rating = (int)$row['rating'];

            ?>

            <div class="feedback-card">


                <div class="student">

                    <div class="student-icon">

                        <?php
                        echo strtoupper(
                            substr(
                                $row['fullname'],
                                0,
                                1
                            )
                        );
                        ?>

                    </div>


                    <div class="student-name">

                        <?php
                        echo htmlspecialchars(
                            $row['fullname']
                        );
                        ?>

                    </div>

                </div>



                <div class="rating">

                    <?php

                    for($i=1; $i<=5; $i++){

                        if($i <= $rating){
                            echo "★";
                        }else{
                            echo "☆";
                        }

                    }

                    ?>

                    <span class="rating-number">
                        <?php echo $rating; ?>/5
                    </span>

                </div>



                <div class="message">

                    💬
                    <?php
                    echo nl2br(
                        htmlspecialchars(
                            $row['message']
                        )
                    );
                    ?>

                </div>



                <div class="date">

                    🕒
                    <?php
                    echo htmlspecialchars(
                        $row['created_at']
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
                💬
            </div>

            <h3>
                No Feedback Available
            </h3>

            <p>
                Students have not submitted any feedback yet.
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