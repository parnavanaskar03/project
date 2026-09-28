<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$role = $_SESSION['role'];

/* Student is not allowed here */
if($role == "student"){
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


/* SOS Requests */
$query = mysqli_query($conn, "
    SELECT sos_requests.*, users.fullname
    FROM sos_requests
    INNER JOIN users
    ON sos_requests.user_id = users.id
    ORDER BY sos_requests.id DESC
");


/* Count SOS */
$total_sos = mysqli_num_rows($query);

$pending_query = mysqli_query($conn, "
    SELECT id FROM sos_requests
    WHERE status='Pending'
");

$pending_sos = mysqli_num_rows($pending_query);


$accepted_query = mysqli_query($conn, "
    SELECT id FROM sos_requests
    WHERE status='Accepted'
");

$accepted_sos = mysqli_num_rows($accepted_query);


$resolved_query = mysqli_query($conn, "
    SELECT id FROM sos_requests
    WHERE status='Resolved'
");

$resolved_sos = mysqli_num_rows($resolved_query);


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

    case "admin":
        $back = "../admin/dashboard.php";
        break;

    default:
        $back = "../login.php";
        break;
}

?>

<!DOCTYPE html>
<html>

<head>

<title>SOS Requests - Campus Care</title>

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
    background:#dc2626;
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
    color:#fee2e2;
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


/* TABLE */

.table-container{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:950px;
}

th{
    background:#f1f5f9;
    text-align:left;
    padding:12px;
    font-size:13px;
}

td{
    padding:12px;
    border-bottom:1px solid #e2e8f0;
    font-size:13px;
    vertical-align:top;
}


/* STATUS */

.status{
    display:inline-block;
    padding:5px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
}

.pending{
    background:#fef3c7;
    color:#92400e;
}

.accepted{
    background:#dbeafe;
    color:#1d4ed8;
}

.resolved{
    background:#dcfce7;
    color:#166534;
}


/* UPDATE FORM */

.update-form{
    display:flex;
    flex-direction:column;
    gap:7px;
    min-width:150px;
}

select,
input[type="text"]{
    padding:8px;
    border:1px solid #cbd5e1;
    border-radius:6px;
    font-size:12px;
    width:100%;
}

.update-btn{
    background:#2563eb;
    color:white;
    border:none;
    padding:8px;
    border-radius:6px;
    cursor:pointer;
    font-size:12px;
}

.update-btn:hover{
    background:#1d4ed8;
}


/* LOCATION */

.location{
    font-weight:bold;
    color:#334155;
}

.message{
    max-width:250px;
    line-height:1.5;
    color:#475569;
}


/* BUTTON */

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

    .stats{
        grid-template-columns:repeat(2,1fr);
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

            <a href="sos_requests.php" class="active">
                🚨 SOS Requests
            </a>

            <a href="notices.php">
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
                🚨 SOS Requests
            </h2>

            <p>
                Campus Care Emergency Management
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
            Emergency SOS Requests 🚨
        </h2>

        <p>
            View and manage emergency requests submitted by students.
        </p>

    </div>



    <!-- STATS -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-icon">
                🚨
            </div>

            <h3>
                <?php echo $total_sos; ?>
            </h3>

            <p>
                Total SOS
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ⏳
            </div>

            <h3>
                <?php echo $pending_sos; ?>
            </h3>

            <p>
                Pending
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                🔄
            </div>

            <h3>
                <?php echo $accepted_sos; ?>
            </h3>

            <p>
                Accepted
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ✅
            </div>

            <h3>
                <?php echo $resolved_sos; ?>
            </h3>

            <p>
                Resolved
            </p>

        </div>

    </div>



    <!-- SOS TABLE -->

    <div class="section">

        <div class="section-header">

            <h2>
                🚨 Emergency Requests
            </h2>

        </div>


        <div class="table-container">

            <table>

                <tr>

                    <th>ID</th>

                    <th>Student</th>

                    <th>Location</th>

                    <th>Message</th>

                    <th>Status</th>

                    <?php

                    if(
                        $role == "security" ||
                        $role == "admin"
                    ){

                    ?>

                    <th>Update</th>

                    <?php

                    }

                    ?>

                    <th>Date</th>

                </tr>


                <?php

                if(mysqli_num_rows($query) > 0){

                    while($row = mysqli_fetch_assoc($query)){

                ?>

                <tr>


                    <td>
                        <?php
                        echo $row['id'];
                        ?>
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

                        <div class="location">

                            📍
                            <?php
                            echo htmlspecialchars(
                                $row['location']
                            );
                            ?>

                        </div>

                    </td>


                    <td>

                        <div class="message">

                            <?php
                            echo htmlspecialchars(
                                $row['message']
                            );
                            ?>

                        </div>

                    </td>


                    <?php

                    if(
                        $role == "security" ||
                        $role == "admin"
                    ){

                    ?>

                    <td>

                        <form
                            action="../admin/update_sos.php"
                            method="POST"
                            class="update-form"
                        >

                            <input
                                type="hidden"
                                name="id"
                                value="<?php
                                echo $row['id'];
                                ?>"
                            >


                            <select name="status" required>

                                <option
                                    value="Pending"
                                    <?php
                                    if(
                                        $row['status']
                                        == "Pending"
                                    )
                                        echo "selected";
                                    ?>
                                >
                                    Pending
                                </option>


                                <option
                                    value="Accepted"
                                    <?php
                                    if(
                                        $row['status']
                                        == "Accepted"
                                    )
                                        echo "selected";
                                    ?>
                                >
                                    Accepted
                                </option>


                                <option
                                    value="Resolved"
                                    <?php
                                    if(
                                        $row['status']
                                        == "Resolved"
                                    )
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
                                value="<?php
                                echo htmlspecialchars(
                                    $row['remarks'] ?? ''
                                );
                                ?>"
                            >


                            <button
                                type="submit"
                                name="update"
                                class="update-btn"
                            >
                                Update
                            </button>

                        </form>

                    </td>


                    <?php

                    }else{

                    ?>

                    <td>

                        <?php

                        if($row['status']=="Pending"){

                            echo '<span class="status pending">
                            Pending
                            </span>';

                        }elseif(
                            $row['status']=="Accepted"
                        ){

                            echo '<span class="status accepted">
                            Accepted
                            </span>';

                        }elseif(
                            $row['status']=="Resolved"
                        ){

                            echo '<span class="status resolved">
                            Resolved
                            </span>';

                        }else{

                            echo '<span class="status pending">'
                            .htmlspecialchars(
                                $row['status']
                            ).
                            '</span>';

                        }

                        ?>

                        <br><br>

                        <small>

                            <?php
                            echo htmlspecialchars(
                                $row['remarks'] ?? ''
                            );
                            ?>

                        </small>

                    </td>

                    <?php

                    }

                    ?>


                    <td>

                        <?php
                        echo $row['created_at'];
                        ?>

                    </td>


                </tr>


                <?php

                    }

                }else{

                ?>


                <tr>

                    <td
                        colspan="<?php
                        echo (
                            $role=="security"
                            || $role=="admin"
                        ) ? 7 : 6;
                        ?>"
                        style="
                        text-align:center;
                        padding:35px;
                        "
                    >

                        🚨 No SOS requests found.

                    </td>

                </tr>


                <?php

                }

                ?>

            </table>

        </div>

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