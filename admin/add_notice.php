<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="admin"){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['publish'])){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $target_role = $_POST['target_role'];

    $posted_by = $_SESSION['role'];

    $status = "Active";

    $sql = "INSERT INTO notices
    (title,description,posted_by,target_role,status)
    VALUES
    ('$title','$description','$posted_by','$target_role','$status')";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Notice Published Successfully');
        window.location='add_notice.php';
        </script>";

    }else{

        echo "<script>
        alert('Failed to Publish Notice');
        </script>";

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Publish Notice</title>

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
    min-height:100vh;
}

/* PAGE */

.notice-admin-page{
    min-height:100vh;
    padding:35px 20px;
}

.notice-admin-container{
    max-width:900px;
    margin:auto;
}

/* HEADER */

.notice-admin-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.notice-admin-header h1{
    color:#172554;
    font-size:28px;
    margin:0;
}

.notice-admin-header p{
    color:#64748b;
    font-size:14px;
    margin-top:7px;
}

.back-dashboard{
    text-decoration:none;
    background:#172554;
    color:white;
    padding:12px 18px;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
}

.back-dashboard:hover{
    background:#2563eb;
}

/* MAIN CARD */

.notice-form-card{
    background:white;
    border-radius:18px;
    padding:30px;
    box-shadow:0 6px 22px rgba(0,0,0,.07);
}

/* TITLE */

.notice-form-title{
    display:flex;
    align-items:center;
    gap:15px;
    padding-bottom:22px;
    margin-bottom:25px;
    border-bottom:1px solid #eef2f7;
}

.notice-icon{
    width:55px;
    height:55px;
    border-radius:14px;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:25px;
}

.notice-form-title h2{
    color:#172554;
    font-size:21px;
    margin:0;
}

.notice-form-title p{
    color:#64748b;
    font-size:12px;
    margin-top:5px;
}

/* FORM */

.form-group{
    margin-bottom:21px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    color:#334155;
    font-size:14px;
    font-weight:600;
}

.form-group input,
.form-group textarea,
.form-group select{
    width:100%;
    border:1px solid #d7dee8;
    border-radius:9px;
    padding:13px 14px;
    font-family:Arial,sans-serif;
    font-size:14px;
    color:#334155;
    background:white;
    outline:none;
}

.form-group input{
    height:47px;
}

.form-group textarea{
    min-height:170px;
    resize:vertical;
    line-height:1.6;
}

.form-group select{
    height:47px;
    cursor:pointer;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.10);
}

/* SMALL TEXT */

.form-help{
    display:block;
    margin-top:6px;
    color:#94a3b8;
    font-size:11px;
}

/* TARGET BOX */

.target-info{
    background:#eff6ff;
    border-left:4px solid #2563eb;
    border-radius:8px;
    padding:14px 16px;
    margin-top:5px;
    margin-bottom:22px;
}

.target-info strong{
    color:#172554;
    font-size:13px;
}

.target-info p{
    color:#475569;
    font-size:12px;
    line-height:1.5;
    margin-top:5px;
}

/* BUTTONS */

.form-buttons{
    display:flex;
    justify-content:flex-end;
    gap:12px;
    margin-top:28px;
    padding-top:22px;
    border-top:1px solid #eef2f7;
}

.cancel-btn{
    text-decoration:none;
    background:#e2e8f0;
    color:#334155;
    padding:13px 22px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
}

.cancel-btn:hover{
    background:#cbd5e1;
}

.publish-btn{
    border:none;
    background:#2563eb;
    color:white;
    padding:13px 24px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
}

.publish-btn:hover{
    background:#1d4ed8;
}

/* PROCESS */

.notice-process{
    background:white;
    border-radius:17px;
    padding:25px 30px;
    margin-top:22px;
    box-shadow:0 5px 18px rgba(0,0,0,.06);
}

.notice-process h3{
    color:#172554;
    font-size:18px;
    margin-bottom:20px;
}

.process-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
}

.process-item{
    text-align:center;
    padding:12px;
}

.process-number{
    width:36px;
    height:36px;
    margin:0 auto 10px;
    border-radius:50%;
    background:#dbeafe;
    color:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}

.process-item strong{
    display:block;
    color:#334155;
    font-size:13px;
}

.process-item p{
    color:#64748b;
    font-size:11px;
    line-height:1.5;
    margin-top:5px;
}

/* MOBILE */

@media(max-width:650px){

    .notice-admin-page{
        padding:20px 12px;
    }

    .notice-admin-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .notice-admin-header h1{
        font-size:24px;
    }

    .back-dashboard{
        width:100%;
        text-align:center;
    }

    .notice-form-card{
        padding:20px;
    }

    .notice-form-title h2{
        font-size:18px;
    }

    .form-buttons{
        flex-direction:column;
    }

    .cancel-btn,
    .publish-btn{
        width:100%;
        text-align:center;
    }

    .process-grid{
        grid-template-columns:1fr;
    }

}

</style>

</head>

<body>

<div class="notice-admin-page">

<div class="notice-admin-container">


    <!-- HEADER -->

    <div class="notice-admin-header">

        <div>

            <h1>
                Notice Management
            </h1>

            <p>
                Publish important announcements for campus users
            </p>

        </div>

        <a
            href="dashboard.php"
            class="back-dashboard"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- FORM CARD -->

    <div class="notice-form-card">


        <div class="notice-form-title">

            <div class="notice-icon">
                📢
            </div>

            <div>

                <h2>
                    Publish New Notice
                </h2>

                <p>
                    Create and share an announcement
                </p>

            </div>

        </div>


        <form method="POST">


            <!-- TITLE -->

            <div class="form-group">

                <label>
                    Notice Title
                </label>

                <input
                    type="text"
                    name="title"
                    placeholder="Enter notice title"
                    required
                >

                <span class="form-help">
                    Use a short and clear title.
                </span>

            </div>


            <!-- DESCRIPTION -->

            <div class="form-group">

                <label>
                    Notice Description
                </label>

                <textarea
                    name="description"
                    placeholder="Write the complete notice here..."
                    required
                ></textarea>

                <span class="form-help">
                    Include all important information related to the notice.
                </span>

            </div>


            <!-- TARGET -->

            <div class="form-group">

                <label>
                    Publish To
                </label>

                <select
                    name="target_role"
                    required
                >

                    <option value="">
                        Select Target Audience
                    </option>

                    <option value="All">
                        All Users
                    </option>

                    <option value="student">
                        Students
                    </option>

                    <option value="teacher">
                        Teachers
                    </option>

                    <option value="hod">
                        HOD
                    </option>

                    <option value="warden">
                        Warden
                    </option>

                    <option value="electrician">
                        Electrician
                    </option>

                    <option value="security">
                        Security
                    </option>

                </select>

            </div>


            <!-- INFO -->

            <div class="target-info">

                <strong>
                    📌 Notice Visibility
                </strong>

                <p>
                    Select who should be able to see this notice.
                    Choosing "All Users" will make the notice visible
                    to everyone with access to Campus Care.
                </p>

            </div>


            <!-- BUTTONS -->

            <div class="form-buttons">

                <a
                    href="dashboard.php"
                    class="cancel-btn"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    name="publish"
                    class="publish-btn"
                >
                    📢 Publish Notice
                </button>

            </div>


        </form>

    </div>


    <!-- PROCESS -->

    <div class="notice-process">

        <h3>
            Notice Publishing Process
        </h3>

        <div class="process-grid">


            <div class="process-item">

                <div class="process-number">
                    1
                </div>

                <strong>
                    Create
                </strong>

                <p>
                    Write the notice and add important information.
                </p>

            </div>


            <div class="process-item">

                <div class="process-number">
                    2
                </div>

                <strong>
                    Select Audience
                </strong>

                <p>
                    Choose which campus users should receive it.
                </p>

            </div>


            <div class="process-item">

                <div class="process-number">
                    3
                </div>

                <strong>
                    Publish
                </strong>

                <p>
                    The notice becomes available on the Notice Board.
                </p>

            </div>


        </div>

    </div>


</div>

</div>

</body>

</html>