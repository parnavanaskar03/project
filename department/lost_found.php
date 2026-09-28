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


/* Lost & Found */
$query = mysqli_query($conn, "
    SELECT lost_found.*, users.fullname
    FROM lost_found
    INNER JOIN users
    ON lost_found.user_id = users.id
    ORDER BY lost_found.id DESC
");


/* Counts */

$total_items = mysqli_num_rows($query);

$lost_query = mysqli_query($conn, "
    SELECT id FROM lost_found
    WHERE status='Lost'
");

$total_lost = mysqli_num_rows($lost_query);


$found_query = mysqli_query($conn, "
    SELECT id FROM lost_found
    WHERE status='Found'
");

$total_found = mysqli_num_rows($found_query);


$returned_query = mysqli_query($conn, "
    SELECT id FROM lost_found
    WHERE status='Returned'
");

$total_returned = mysqli_num_rows($returned_query);


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

<title>Lost & Found - Campus Care</title>

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
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin-bottom:20px;
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


/* ITEM GRID */

.item-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:18px;
}

.item-card{
    border:1px solid #e2e8f0;
    border-radius:10px;
    overflow:hidden;
    background:#f8fafc;
    transition:0.2s;
}

.item-card:hover{
    transform:translateY(-3px);
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}


/* IMAGE */

.item-image{
    width:100%;
    height:180px;
    object-fit:cover;
    background:#e2e8f0;
}

.no-image{
    height:180px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#e2e8f0;
    color:#64748b;
    font-size:35px;
}


/* ITEM CONTENT */

.item-content{
    padding:16px;
}

.item-content h3{
    font-size:17px;
    color:#172554;
    margin-bottom:8px;
}

.student{
    font-size:12px;
    color:#475569;
    margin-bottom:8px;
}

.description{
    font-size:13px;
    color:#64748b;
    line-height:1.5;
    margin-bottom:10px;
}

.location{
    font-size:12px;
    color:#475569;
    margin-bottom:12px;
}


/* STATUS */

.status{
    display:inline-block;
    padding:5px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
}

.lost{
    background:#fee2e2;
    color:#b91c1c;
}

.found{
    background:#dbeafe;
    color:#1d4ed8;
}

.returned{
    background:#dcfce7;
    color:#166534;
}

.unknown{
    background:#e2e8f0;
    color:#475569;
}


/* DATE */

.date{
    margin-top:12px;
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

@media(max-width:1100px){

    .item-grid{
        grid-template-columns:repeat(2,1fr);
    }

    .stats{
        grid-template-columns:repeat(2,1fr);
    }

}

@media(max-width:900px){

    .sidebar{
        width:200px;
    }

    .main{
        margin-left:200px;
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
    }

    .item-grid{
        grid-template-columns:1fr;
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

            <a href="lost_found.php" class="active">
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
                📦 Lost & Found
            </h2>

            <p>
                Campus Care Lost & Found Management
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
            Lost & Found Items 📦
        </h2>

        <p>
            View items reported by students and track their current status.
        </p>

    </div>



    <!-- STATS -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-icon">
                📦
            </div>

            <h3>
                <?php echo $total_items; ?>
            </h3>

            <p>
                Total Items
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                🔴
            </div>

            <h3>
                <?php echo $total_lost; ?>
            </h3>

            <p>
                Lost
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                🔵
            </div>

            <h3>
                <?php echo $total_found; ?>
            </h3>

            <p>
                Found
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ✅
            </div>

            <h3>
                <?php echo $total_returned; ?>
            </h3>

            <p>
                Returned
            </p>

        </div>

    </div>



    <!-- ITEMS -->

    <div class="section">

        <div class="section-header">

            <h2>
                📦 Reported Items
            </h2>

        </div>


        <?php

        if($total_items > 0){

        ?>

        <div class="item-grid">

            <?php

            while($row = mysqli_fetch_assoc($query)){

                $status = $row['status'];

            ?>

            <div class="item-card">


                <?php

                if(
                    !empty($row['image']) &&
                    file_exists("../uploads/".$row['image'])
                ){

                ?>

                    <img
                        src="../uploads/<?php
                        echo htmlspecialchars($row['image']);
                        ?>"
                        class="item-image"
                        alt="Lost or Found Item"
                    >

                <?php

                }else{

                ?>

                    <div class="no-image">
                        📦
                    </div>

                <?php

                }

                ?>


                <div class="item-content">


                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $row['item_name']
                        );
                        ?>

                    </h3>


                    <div class="student">

                        👤 Student:
                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $row['fullname']
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="description">

                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $row['description']
                            )
                        );
                        ?>

                    </div>


                    <div class="location">

                        📍
                        <?php
                        echo htmlspecialchars(
                            $row['location']
                        );
                        ?>

                    </div>


                    <?php

                    if($status == "Lost"){

                        echo '<span class="status lost">
                        Lost
                        </span>';

                    }elseif($status == "Found"){

                        echo '<span class="status found">
                        Found
                        </span>';

                    }elseif($status == "Returned"){

                        echo '<span class="status returned">
                        Returned
                        </span>';

                    }else{

                        echo '<span class="status unknown">'
                        .htmlspecialchars($status).
                        '</span>';

                    }

                    ?>


                    <div class="date">

                        🕒
                        <?php
                        echo htmlspecialchars(
                            $row['created_at']
                        );
                        ?>

                    </div>

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
                📦
            </div>

            <h3>
                No Lost & Found Items
            </h3>

            <p>
                No items have been reported yet.
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