<?php

session_start();

include("../config/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: ../login.php");
    exit();

}

if($_SESSION['role'] != "admin"){

    header("Location: ../login.php");
    exit();

}

$id = $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$id'"
);

$user = mysqli_fetch_assoc($query);

if(!$user){

    die("Admin profile not found.");

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Admin Profile</title>

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

.profile-page{
    min-height:100vh;
    padding:35px 20px;
}

.profile-container{
    max-width:900px;
    margin:auto;
}

/* HEADER */

.profile-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.profile-header h1{
    color:#172554;
    font-size:28px;
}

.profile-header p{
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

/* MAIN CARD */

.profile-card{
    background:white;
    border-radius:18px;
    padding:30px;
    box-shadow:0 6px 22px rgba(0,0,0,.07);
}

/* PROFILE TOP */

.profile-top{
    display:flex;
    align-items:center;
    gap:22px;
    padding-bottom:25px;
    margin-bottom:25px;
    border-bottom:1px solid #eef2f7;
}

.profile-image{
    width:120px;
    height:120px;
    min-width:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #dbeafe;
}

.profile-top-info h2{
    color:#172554;
    font-size:22px;
}

.profile-top-info p{
    color:#64748b;
    font-size:13px;
    margin-top:6px;
}

.admin-badge{
    display:inline-block;
    margin-top:10px;
    background:#dbeafe;
    color:#1d4ed8;
    padding:6px 12px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
}

/* FORM */

.form-title{
    color:#172554;
    font-size:18px;
    margin-bottom:20px;
}

.form-group{
    margin-bottom:19px;
}

.form-group label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:600;
    margin-bottom:8px;
}

.form-group input{
    width:100%;
    height:46px;
    padding:0 14px;
    border:1px solid #d7dee8;
    border-radius:9px;
    font-family:Arial,sans-serif;
    font-size:13px;
    color:#334155;
    background:white;
    outline:none;
}

.form-group input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.10);
}

.form-group input[readonly]{
    background:#f8fafc;
    color:#64748b;
    cursor:not-allowed;
}

.file-input{
    height:auto !important;
    padding:11px 12px !important;
    cursor:pointer;
}

.form-help{
    display:block;
    margin-top:6px;
    color:#94a3b8;
    font-size:11px;
}

/* ACCOUNT INFO */

.account-info{
    background:#eff6ff;
    border-left:4px solid #2563eb;
    border-radius:9px;
    padding:15px 17px;
    margin-top:5px;
}

.account-info strong{
    color:#172554;
    font-size:13px;
}

.account-info p{
    color:#475569;
    font-size:11px;
    line-height:1.5;
    margin-top:5px;
}

/* BUTTONS */

.profile-buttons{
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:25px;
    padding-top:22px;
    border-top:1px solid #eef2f7;
}

.cancel-btn{
    text-decoration:none;
    background:#e2e8f0;
    color:#334155;
    padding:13px 22px;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
}

.cancel-btn:hover{
    background:#cbd5e1;
}

.update-btn{
    border:none;
    background:#2563eb;
    color:white;
    padding:13px 24px;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
}

.update-btn:hover{
    background:#1d4ed8;
}

/* SECURITY INFO */

.security-box{
    background:white;
    border-radius:17px;
    padding:23px 25px;
    margin-top:22px;
    box-shadow:0 5px 18px rgba(0,0,0,.06);
}

.security-box h3{
    color:#172554;
    font-size:17px;
    margin-bottom:15px;
}

.security-item{
    display:flex;
    align-items:center;
    gap:12px;
    background:#f8fafc;
    border-radius:9px;
    padding:13px;
    margin-top:9px;
}

.security-icon{
    font-size:19px;
}

.security-item strong{
    display:block;
    color:#334155;
    font-size:12px;
}

.security-item span{
    display:block;
    color:#64748b;
    font-size:11px;
    margin-top:3px;
}

/* MOBILE */

@media(max-width:650px){

    .profile-page{
        padding:20px 12px;
    }

    .profile-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .profile-header h1{
        font-size:24px;
    }

    .back-btn{
        width:100%;
        text-align:center;
    }

    .profile-card{
        padding:20px;
    }

    .profile-top{
        flex-direction:column;
        text-align:center;
    }

    .profile-top-info h2{
        font-size:20px;
    }

    .profile-buttons{
        flex-direction:column;
    }

    .cancel-btn,
    .update-btn{
        width:100%;
        text-align:center;
    }

}

</style>

</head>

<body>

<div class="profile-page">

<div class="profile-container">


    <!-- HEADER -->

    <div class="profile-header">

        <div>

            <h1>
                Admin Profile
            </h1>

            <p>
                View and update your administrator account
            </p>

        </div>


        <a
            href="dashboard.php"
            class="back-btn"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- PROFILE CARD -->

    <div class="profile-card">


        <!-- PROFILE TOP -->

        <div class="profile-top">

            <?php

            if(!empty($user['profile_pic'])){

            ?>

                <img
                    src="../uploads/<?php
                    echo htmlspecialchars(
                        $user['profile_pic']
                    );
                    ?>"
                    class="profile-image"
                    alt="Admin Profile"
                >

            <?php

            }else{

            ?>

                <img
                    src="../assets/images/user.png"
                    class="profile-image"
                    alt="Admin Profile"
                >

            <?php

            }

            ?>


            <div class="profile-top-info">

                <h2>

                    <?php

                    echo htmlspecialchars(
                        $user['fullname']
                    );

                    ?>

                </h2>

                <p>
                    Campus Care Administrator
                </p>

                <span class="admin-badge">
                    ADMIN
                </span>

            </div>

        </div>


        <!-- FORM TITLE -->

        <h3 class="form-title">
            Account Information
        </h3>


        <!-- FORM -->

        <form
            action="update_profile.php"
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- FULL NAME -->

            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="fullname"
                    value="<?php
                    echo htmlspecialchars(
                        $user['fullname']
                    );
                    ?>"
                    required
                >

            </div>


            <!-- STUDENT ID -->

            <div class="form-group">

                <label>
                    Student / User ID
                </label>

                <input
                    type="text"
                    name="student_id"
                    value="<?php
                    echo htmlspecialchars(
                        $user['student_id']
                    );
                    ?>"
                    readonly
                >

                <span class="form-help">
                    User ID cannot be changed.
                </span>

            </div>


            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="<?php
                    echo htmlspecialchars(
                        $user['email']
                    );
                    ?>"
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
                    value="<?php
                    echo htmlspecialchars(
                        $user['phone']
                    );
                    ?>"
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
                    value="<?php
                    echo htmlspecialchars(
                        $user['department']
                    );
                    ?>"
                >

            </div>


            <!-- ROLE -->

            <div class="form-group">

                <label>
                    Account Role
                </label>

                <input
                    type="text"
                    value="Administrator"
                    readonly
                >

                <span class="form-help">
                    Admin role is controlled by the system.
                </span>

            </div>


            <!-- PROFILE PIC -->

            <div class="form-group">

                <label>
                    Profile Picture
                </label>

                <input
                    type="file"
                    name="profile_pic"
                    class="file-input"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <span class="form-help">
                    JPG, JPEG, PNG or WEBP image.
                </span>

            </div>


            <!-- INFO -->

            <div class="account-info">

                <strong>
                    🔐 Account Information
                </strong>

                <p>
                    Your profile information is connected with
                    your Campus Care administrator account.
                    Keep your email and phone number updated.
                </p>

            </div>


            <!-- BUTTONS -->

            <div class="profile-buttons">

                <a
                    href="dashboard.php"
                    class="cancel-btn"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    name="update"
                    class="update-btn"
                >
                    💾 Update Profile
                </button>

            </div>


        </form>


    </div>


    <!-- SECURITY -->

    <div class="security-box">

        <h3>
            🔒 Account Security
        </h3>


        <div class="security-item">

            <div class="security-icon">
                🛡️
            </div>

            <div>

                <strong>
                    Administrator Access
                </strong>

                <span>
                    You have access to Campus Care management features.
                </span>

            </div>

        </div>


        <div class="security-item">

            <div class="security-icon">
                🔑
            </div>

            <div>

                <strong>
                    Password Protected
                </strong>

                <span>
                    Your account password is stored securely.
                </span>

            </div>

        </div>


    </div>


</div>

</div>

</body>

</html>