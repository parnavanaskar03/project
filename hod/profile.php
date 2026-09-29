<?php

session_start();
include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] != "hod") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User profile not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>HOD Profile - Campus Care</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }


        body {
            background: #f4f7fb;
            color: #1e293b;
        }


        /* SIDEBAR */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 245px;
            height: 100vh;
            background: #172554;
            color: white;
            padding: 22px 15px;
        }


        .logo {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }


        .logo span {
            color: #60a5fa;
        }


        .menu-title {
            font-size: 12px;
            color: #93c5fd;
            margin: 18px 12px 8px;
            text-transform: uppercase;
        }


        .sidebar a {
            display: block;
            text-decoration: none;
            color: #dbeafe;
            padding: 12px 14px;
            margin: 5px 0;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }


        .sidebar a:hover,
        .sidebar a.active {
            background: #2563eb;
            color: white;
        }


        .logout {
            margin-top: 25px !important;
            background: #991b1b;
        }


        .logout:hover {
            background: #dc2626 !important;
        }


        /* MAIN */

        .main {
            margin-left: 245px;
            min-height: 100vh;
        }


        /* TOPBAR */

        .topbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }


        .topbar h2 {
            font-size: 20px;
        }


        .topbar-right {
            color: #64748b;
            font-size: 14px;
        }


        /* CONTENT */

        .content {
            padding: 30px;
        }


        /* WELCOME */

        .welcome {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            padding: 25px;
            border-radius: 14px;
            margin-bottom: 25px;
        }


        .welcome h1 {
            font-size: 25px;
            margin-bottom: 8px;
        }


        .welcome p {
            color: #dbeafe;
            font-size: 14px;
        }


        /* PROFILE CARD */

        .profile-card {
            background: white;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.06);
        }


        /* PROFILE HEADER */

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 25px;
            margin-bottom: 25px;
        }


        .profile-picture {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #dbeafe;
        }


        .profile-placeholder {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #dbeafe;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
            font-weight: bold;
        }


        .profile-header h2 {
            font-size: 22px;
            margin-bottom: 6px;
        }


        .role {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }


        /* FORM */

        .form-title {
            font-size: 18px;
            margin-bottom: 20px;
        }


        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }


        .form-group {
            display: flex;
            flex-direction: column;
        }


        .form-group.full {
            grid-column: span 2;
        }


        label {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #475569;
        }


        input {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
        }


        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #dbeafe;
        }


        input:disabled {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }


        .file-input {
            padding: 10px;
            background: #f8fafc;
        }


        /* BUTTONS */

        .button-area {
            margin-top: 25px;
            display: flex;
            gap: 12px;
        }


        .btn {
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
        }


        .btn-primary {
            background: #2563eb;
            color: white;
        }


        .btn-primary:hover {
            background: #1d4ed8;
        }


        .btn-secondary {
            background: #e2e8f0;
            color: #334155;
        }


        .btn-secondary:hover {
            background: #cbd5e1;
        }


        /* INFO */

        .info-box {
            margin-top: 25px;
            padding: 15px;
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            border-radius: 6px;
            font-size: 13px;
            color: #475569;
        }


        /* FOOTER */

        footer {
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            padding: 25px;
        }


        /* RESPONSIVE */

        @media (max-width: 850px) {

            .sidebar {
                width: 210px;
            }

            .main {
                margin-left: 210px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full {
                grid-column: span 1;
            }

        }


        @media (max-width: 650px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                padding: 0 15px;
            }

            .content {
                padding: 15px;
            }

            .profile-header {
                flex-direction: column;
                text-align: center;
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


    <div class="menu-title">
        HOD Menu
    </div>


    <a href="dashboard.php">
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


    <a href="profile.php" class="active">
        👤 My Profile
    </a>


    <a href="../logout.php" class="logout">
        🚪 Logout
    </a>

</div>



<!-- MAIN -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <h2>
            My Profile
        </h2>


        <div class="topbar-right">

            👨‍💼 HOD Account

        </div>

    </div>



    <!-- CONTENT -->

    <div class="content">


        <!-- WELCOME -->

        <div class="welcome">

            <h1>

                👋 Hello,
                <?php echo htmlspecialchars($user['fullname']); ?>

            </h1>


            <p>

                Manage your personal information and profile details.

            </p>

        </div>



        <!-- PROFILE CARD -->

        <div class="profile-card">


            <!-- PROFILE HEADER -->

            <div class="profile-header">


                <?php if (!empty($user['profile_pic'])) { ?>


                    <img
                        src="../uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>"
                        class="profile-picture"
                        alt="Profile Picture"
                    >


                <?php } else { ?>


                    <div class="profile-placeholder">

                        <?php

                        echo strtoupper(
                            substr($user['fullname'], 0, 1)
                        );

                        ?>

                    </div>


                <?php } ?>


                <div>

                    <h2>

                        <?php

                        echo htmlspecialchars(
                            $user['fullname']
                        );

                        ?>

                    </h2>


                    <span class="role">

                        👨‍💼 HOD

                    </span>

                </div>


            </div>



            <!-- TITLE -->

            <h3 class="form-title">

                Personal Information

            </h3>



            <!-- FORM -->

            <form
                action="update_profile.php"
                method="POST"
                enctype="multipart/form-data"
            >


                <div class="form-grid">


                    <!-- FULL NAME -->

                    <div class="form-group">

                        <label>
                            Full Name
                        </label>


                        <input
                            type="text"
                            name="fullname"
                            value="<?php echo htmlspecialchars($user['fullname']); ?>"
                            required
                        >

                    </div>



                    <!-- EMAIL -->

                    <div class="form-group">

                        <label>
                            Email
                        </label>


                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($user['email']); ?>"
                            required
                        >

                    </div>



                    <!-- PHONE -->

                    <div class="form-group">

                        <label>
                            Phone Number
                        </label>


                        <input
                            type="text"
                            name="phone"
                            value="<?php echo htmlspecialchars($user['phone']); ?>"
                        >

                    </div>



                    <!-- DEPARTMENT -->

                    <div class="form-group">

                        <label>
                            Department
                        </label>


                        <input
                            type="text"
                            name="department"
                            value="<?php echo htmlspecialchars($user['department']); ?>"
                        >

                    </div>



                    <!-- STUDENT ID -->

                    <div class="form-group">

                        <label>
                            Student ID
                        </label>


                        <input
                            type="text"
                            value="<?php echo htmlspecialchars($user['student_id']); ?>"
                            disabled
                        >

                    </div>



                    <!-- ROLE -->

                    <div class="form-group">

                        <label>
                            Role
                        </label>


                        <input
                            type="text"
                            value="HOD"
                            disabled
                        >

                    </div>



                    <!-- PROFILE PICTURE -->

                    <div class="form-group full">

                        <label>
                            Profile Picture
                        </label>


                        <input
                            type="file"
                            name="profile_pic"
                            accept="image/*"
                            class="file-input"
                        >

                    </div>


                </div>



                <!-- BUTTONS -->

                <div class="button-area">


                    <button
                        type="submit"
                        name="update"
                        class="btn btn-primary"
                    >

                        💾 Update Profile

                    </button>



                    <a
                        href="dashboard.php"
                        class="btn btn-secondary"
                    >

                        ← Back to Dashboard

                    </a>


                </div>


            </form>



            <!-- INFORMATION -->

            <div class="info-box">

                🔒 <strong>Account Information:</strong>

                Student ID and Role cannot be changed from the profile page.

            </div>


        </div>


    </div>



    <!-- FOOTER -->

    <footer>

        © 2026 Campus Care – Smart Campus Management System

    </footer>


</div>


</body>

</html>