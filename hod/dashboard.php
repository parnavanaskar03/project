<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "hod"){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


/* HOD Information */

$user_query = mysqli_query($conn, "
    SELECT * FROM users
    WHERE id='$user_id'
");

$hod = mysqli_fetch_assoc($user_query);

if(!$hod){
    die("HOD profile not found.");
}

$department = $hod['department'];


/* Department Teachers */

$teacher_query = mysqli_query($conn, "
    SELECT id, fullname
    FROM users
    WHERE role='teacher'
    AND department='$department'
    ORDER BY fullname ASC
");


/* Department Complaints */

$query = mysqli_query($conn, "
    SELECT complaints.*, users.fullname
    FROM complaints
    INNER JOIN users
    ON complaints.user_id = users.id
    WHERE users.department='$department'
    ORDER BY complaints.id DESC
");


/* Statistics */

$total_complaints = mysqli_num_rows($query);


$pending_query = mysqli_query($conn, "
    SELECT complaints.id
    FROM complaints
    INNER JOIN users
    ON complaints.user_id = users.id
    WHERE users.department='$department'
    AND complaints.status='Pending'
");

$pending = mysqli_num_rows($pending_query);


$progress_query = mysqli_query($conn, "
    SELECT complaints.id
    FROM complaints
    INNER JOIN users
    ON complaints.user_id = users.id
    WHERE users.department='$department'
    AND complaints.status='In Progress'
");

$in_progress = mysqli_num_rows($progress_query);


$resolved_query = mysqli_query($conn, "
    SELECT complaints.id
    FROM complaints
    INNER JOIN users
    ON complaints.user_id = users.id
    WHERE users.department='$department'
    AND complaints.status='Resolved'
");

$resolved = mysqli_num_rows($resolved_query);

?>

<!DOCTYPE html>
<html>

<head>

<title>HOD Dashboard - Campus Care</title>

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


/* Sidebar */

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
    transition:0.2s;
}

.nav a:hover{
    background:#1e40af;
    color:white;
}

.nav a.active{
    background:#2563eb;
    color:white;
}

.logout{
    margin-top:25px;
    color:#fecaca !important;
}

.logout:hover{
    background:#991b1b !important;
}


/* Main */

.main{
    margin-left:240px;
    padding:25px;
}


/* Topbar */

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


/* Welcome */

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


/* Stats */

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


/* Section */

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


/* HOD Info */

.info-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
}

.info-box{
    background:#f8fafc;
    padding:15px;
    border-radius:8px;
}

.info-box span{
    display:block;
    font-size:12px;
    color:#64748b;
    margin-bottom:5px;
}

.info-box strong{
    font-size:14px;
}


/* Table */

.table-container{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
    min-width:1100px;
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


/* Status */

.status{
    padding:5px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
}

.pending{
    background:#fef3c7;
    color:#92400e;
}

.progress{
    background:#dbeafe;
    color:#1d4ed8;
}

.resolved{
    background:#dcfce7;
    color:#166534;
}


/* Form */

.update-form,
.assign-form{
    display:flex;
    flex-direction:column;
    gap:7px;
}

select,
input[type="text"]{
    padding:8px;
    border:1px solid #cbd5e1;
    border-radius:6px;
    font-size:12px;
    width:100%;
}

select:focus,
input[type="text"]:focus{
    outline:none;
    border-color:#2563eb;
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

.assign-btn{
    background:#16a34a;
    color:white;
    border:none;
    padding:8px;
    border-radius:6px;
    cursor:pointer;
    font-size:12px;
}

.assign-btn:hover{
    background:#15803d;
}


/* Footer */

.footer{
    text-align:center;
    padding:20px;
    color:#64748b;
    font-size:12px;
}


/* Responsive */

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

    .info-grid{
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
            echo strtoupper(substr($hod['fullname'],0,1));
            ?>

        </div>

        <h3>
            <?php echo htmlspecialchars($hod['fullname']); ?>
        </h3>

        <p>
            HOD
        </p>

    </div>


    <div class="nav">

        <a href="dashboard.php" class="active">
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
                HOD Dashboard
            </h2>

            <p>
                Campus Care Management System
            </p>

        </div>

        <div>
            👨‍💼 HOD
        </div>

    </div>



    <!-- WELCOME -->

    <div class="welcome">

        <h2>

            Welcome,
            <?php echo htmlspecialchars($hod['fullname']); ?>
            👋

        </h2>

        <p>
            Monitor, review and assign complaints from your department.
        </p>

    </div>



    <!-- STATS -->

    <div class="stats">

        <div class="stat-card">

            <div class="stat-icon">
                📋
            </div>

            <h3>
                <?php echo $total_complaints; ?>
            </h3>

            <p>
                Department Complaints
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ⏳
            </div>

            <h3>
                <?php echo $pending; ?>
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
                <?php echo $in_progress; ?>
            </h3>

            <p>
                In Progress
            </p>

        </div>


        <div class="stat-card">

            <div class="stat-icon">
                ✅
            </div>

            <h3>
                <?php echo $resolved; ?>
            </h3>

            <p>
                Resolved
            </p>

        </div>

    </div>



    <!-- HOD INFORMATION -->

    <div class="section">

        <div class="section-header">

            <h2>
                👨‍💼 HOD Information
            </h2>

        </div>


        <div class="info-grid">

            <div class="info-box">

                <span>
                    Full Name
                </span>

                <strong>
                    <?php echo htmlspecialchars($hod['fullname']); ?>
                </strong>

            </div>


            <div class="info-box">

                <span>
                    Email
                </span>

                <strong>
                    <?php echo htmlspecialchars($hod['email']); ?>
                </strong>

            </div>


            <div class="info-box">

                <span>
                    Department
                </span>

                <strong>
                    <?php echo htmlspecialchars($hod['department']); ?>
                </strong>

            </div>

        </div>

    </div>



    <!-- DEPARTMENT COMPLAINTS -->

    <div class="section">

        <div class="section-header">

            <h2>
                📋 Department Complaints
            </h2>

        </div>


        <div class="table-container">

            <table>

                <tr>

                    <th>ID</th>

                    <th>Student</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Status</th>

                    <th>Assigned To</th>

                    <th>Update Status</th>

                    <th>Date</th>

                </tr>


                <?php

                if(mysqli_num_rows($query) > 0){

                    while($row = mysqli_fetch_assoc($query)){

                ?>

                <tr>

                    <td>
                        <?php echo $row['id']; ?>
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

                        <?php

                        if($row['status']=="Pending"){

                            echo '<span class="status pending">
                                    Pending
                                  </span>';

                        }

                        elseif($row['status']=="In Progress"){

                            echo '<span class="status progress">
                                    In Progress
                                  </span>';

                        }

                        elseif($row['status']=="Resolved"){

                            echo '<span class="status resolved">
                                    Resolved
                                  </span>';

                        }

                        else{

                            echo '<span class="status pending">'
                                .htmlspecialchars($row['status']).
                                '</span>';

                        }

                        ?>

                    </td>


                    <!-- ASSIGN TEACHER -->

                    <td>

                        <form
                            action="assign_complaint.php"
                            method="POST"
                            class="assign-form"
                        >

                            <input
                                type="hidden"
                                name="complaint_id"
                                value="<?php echo $row['id']; ?>"
                            >

                            <select
                                name="assigned_to"
                                required
                            >

                                <option value="">
                                    Select Teacher
                                </option>

                                <?php

                                mysqli_data_seek(
                                    $teacher_query,
                                    0
                                );

                                while(
                                    $teacher =
                                    mysqli_fetch_assoc(
                                        $teacher_query
                                    )
                                ){

                                ?>

                                <option
                                    value="<?php echo $teacher['id']; ?>"

                                    <?php

                                    if(
                                        $row['assigned_to']
                                        ==
                                        $teacher['id']
                                    ){
                                        echo "selected";
                                    }

                                    ?>

                                >

                                    <?php
                                    echo htmlspecialchars(
                                        $teacher['fullname']
                                    );
                                    ?>

                                </option>

                                <?php

                                }

                                ?>

                            </select>


                            <button
                                type="submit"
                                name="assign"
                                class="assign-btn"
                            >
                                Assign
                            </button>

                        </form>

                    </td>


                    <!-- UPDATE STATUS -->

                    <td>

                        <form
                            action="update_status.php"
                            method="POST"
                            class="update-form"
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

                                <option
                                    value="Pending"
                                    <?php
                                    if(
                                        $row['status']
                                        ==
                                        "Pending"
                                    )
                                        echo "selected";
                                    ?>
                                >
                                    Pending
                                </option>


                                <option
                                    value="In Progress"
                                    <?php
                                    if(
                                        $row['status']
                                        ==
                                        "In Progress"
                                    )
                                        echo "selected";
                                    ?>
                                >
                                    In Progress
                                </option>


                                <option
                                    value="Resolved"
                                    <?php
                                    if(
                                        $row['status']
                                        ==
                                        "Resolved"
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


                    <td>
                        <?php echo $row['created_at']; ?>
                    </td>

                </tr>


                <?php

                    }

                }

                else{

                ?>

                <tr>

                    <td
                        colspan="8"
                        style="text-align:center;padding:30px;"
                    >

                        No complaints found for your department.

                    </td>

                </tr>

                <?php

                }

                ?>

            </table>

        </div>

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