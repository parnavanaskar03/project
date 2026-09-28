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

$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - User Management</title>

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

.users-page{
    min-height:100vh;
    padding:35px 20px;
}

.users-container{
    max-width:1200px;
    margin:auto;
}


/* HEADER */

.users-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.users-header h1{
    color:#172554;
    font-size:28px;
}

.users-header p{
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


/* INFO */

.users-info{
    background:#eff6ff;
    border-left:5px solid #2563eb;
    padding:17px 20px;
    border-radius:10px;
    margin-bottom:22px;
}

.users-info strong{
    color:#172554;
    font-size:14px;
}

.users-info p{
    color:#475569;
    font-size:12px;
    line-height:1.5;
    margin-top:5px;
}


/* CARD */

.users-card{
    background:white;
    border-radius:17px;
    padding:25px;
    box-shadow:0 6px 22px rgba(0,0,0,.07);
}

.users-card-title{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.users-card-title h2{
    color:#172554;
    font-size:20px;
}

.user-count{
    background:#dbeafe;
    color:#1d4ed8;
    padding:7px 13px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
}


/* TABLE */

.table-wrapper{
    width:100%;
    overflow-x:auto;
}

.users-table{
    width:100%;
    border-collapse:collapse;
    min-width:900px;
}

.users-table th{
    background:#172554;
    color:white;
    padding:13px 12px;
    text-align:left;
    font-size:12px;
    font-weight:600;
}

.users-table th:first-child{
    border-radius:8px 0 0 0;
}

.users-table th:last-child{
    border-radius:0 8px 0 0;
}

.users-table td{
    padding:14px 12px;
    border-bottom:1px solid #eef2f7;
    font-size:12px;
    color:#475569;
    vertical-align:middle;
}

.users-table tr:hover td{
    background:#f8fafc;
}


/* USER */

.user-name{
    display:flex;
    align-items:center;
    gap:10px;
}

.user-avatar{
    width:38px;
    height:38px;
    border-radius:50%;
    object-fit:cover;
    background:#dbeafe;
}

.user-avatar-default{
    width:38px;
    height:38px;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:17px;
}

.user-name strong{
    color:#172554;
    font-size:12px;
}


/* ROLE */

.role-badge{
    display:inline-block;
    padding:6px 10px;
    border-radius:15px;
    font-size:10px;
    font-weight:bold;
    text-transform:capitalize;
}

.role-student{
    background:#dbeafe;
    color:#1d4ed8;
}

.role-teacher{
    background:#dcfce7;
    color:#15803d;
}

.role-hod{
    background:#f3e8ff;
    color:#7e22ce;
}

.role-warden{
    background:#ffedd5;
    color:#c2410c;
}

.role-electrician{
    background:#fef3c7;
    color:#92400e;
}

.role-security{
    background:#fee2e2;
    color:#b91c1c;
}

.role-admin{
    background:#e0e7ff;
    color:#3730a3;
}

.role-other{
    background:#e2e8f0;
    color:#475569;
}


/* BUTTONS */

.action-buttons{
    display:flex;
    gap:7px;
}

.edit-btn{
    text-decoration:none;
    background:#dbeafe;
    color:#1d4ed8;
    padding:8px 12px;
    border-radius:7px;
    font-size:11px;
    font-weight:bold;
}

.edit-btn:hover{
    background:#bfdbfe;
}

.delete-btn{
    text-decoration:none;
    background:#fee2e2;
    color:#b91c1c;
    padding:8px 12px;
    border-radius:7px;
    font-size:11px;
    font-weight:bold;
}

.delete-btn:hover{
    background:#fecaca;
}


/* EMPTY */

.empty-box{
    text-align:center;
    padding:50px 20px;
    color:#64748b;
}

.empty-icon{
    font-size:40px;
}

.empty-box h3{
    color:#172554;
    margin-top:12px;
}

.empty-box p{
    margin-top:6px;
    font-size:13px;
}


/* BOTTOM */

.users-bottom{
    margin-top:20px;
}

.users-bottom a{
    text-decoration:none;
    color:#2563eb;
    font-size:13px;
    font-weight:600;
}

.users-bottom a:hover{
    text-decoration:underline;
}


/* MOBILE */

@media(max-width:700px){

    .users-page{
        padding:20px 12px;
    }

    .users-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .users-header h1{
        font-size:24px;
    }

    .back-btn{
        width:100%;
        text-align:center;
    }

    .users-card{
        padding:18px;
    }

    .users-card-title{
        align-items:flex-start;
        gap:10px;
    }

}

</style>

</head>

<body>

<div class="users-page">

<div class="users-container">


    <!-- HEADER -->

    <div class="users-header">

        <div>

            <h1>
                User Management
            </h1>

            <p>
                Manage students, teachers and other campus users
            </p>

        </div>

        <a
            href="dashboard.php"
            class="back-btn"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- INFO -->

    <div class="users-info">

        <strong>
            👥 Campus Users
        </strong>

        <p>
            View registered users, their department and role.
            Admin can edit or delete user accounts from here.
        </p>

    </div>


    <!-- USERS CARD -->

    <div class="users-card">


        <div class="users-card-title">

            <h2>
                All Users
            </h2>

            <span class="user-count">

                <?php
                echo mysqli_num_rows($query);
                ?>
                Users

            </span>

        </div>


        <?php

        if(mysqli_num_rows($query) > 0){

        ?>

        <div class="table-wrapper">

        <table class="users-table">

            <tr>

                <th>ID</th>

                <th>User</th>

                <th>Student ID</th>

                <th>Email</th>

                <th>Phone</th>

                <th>Department</th>

                <th>Role</th>

                <th>Action</th>

            </tr>


            <?php

            while($row = mysqli_fetch_assoc($query)){

                $role = strtolower($row['role']);

                $role_class = "role-other";

                if($role == "student"){
                    $role_class = "role-student";
                }
                elseif($role == "teacher"){
                    $role_class = "role-teacher";
                }
                elseif($role == "hod"){
                    $role_class = "role-hod";
                }
                elseif($role == "warden"){
                    $role_class = "role-warden";
                }
                elseif($role == "electrician"){
                    $role_class = "role-electrician";
                }
                elseif($role == "security"){
                    $role_class = "role-security";
                }
                elseif($role == "admin"){
                    $role_class = "role-admin";
                }

            ?>


            <tr>

                <!-- ID -->

                <td>
                    #<?php
                    echo htmlspecialchars($row['id']);
                    ?>
                </td>


                <!-- USER -->

                <td>

                    <div class="user-name">


                        <?php

                        if(!empty($row['profile_pic'])){

                        ?>

                            <img
                                src="../uploads/<?php
                                echo htmlspecialchars(
                                    $row['profile_pic']
                                );
                                ?>"
                                class="user-avatar"
                                alt="Profile"
                            >

                        <?php

                        }
                        else{

                        ?>

                            <div class="user-avatar-default">
                                👤
                            </div>

                        <?php

                        }

                        ?>


                        <strong>

                            <?php

                            echo htmlspecialchars(
                                $row['fullname']
                            );

                            ?>

                        </strong>


                    </div>

                </td>


                <!-- STUDENT ID -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $row['student_id']
                    );

                    ?>

                </td>


                <!-- EMAIL -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $row['email']
                    );

                    ?>

                </td>


                <!-- PHONE -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $row['phone']
                    );

                    ?>

                </td>


                <!-- DEPARTMENT -->

                <td>

                    <?php

                    echo htmlspecialchars(
                        $row['department']
                    );

                    ?>

                </td>


                <!-- ROLE -->

                <td>

                    <span class="role-badge <?php
                    echo $role_class;
                    ?>">

                        <?php

                        echo htmlspecialchars(
                            $row['role']
                        );

                        ?>

                    </span>

                </td>


                <!-- ACTION -->

                <td>

                    <div class="action-buttons">


                        <a
                            href="edit_user.php?id=<?php
                            echo htmlspecialchars(
                                $row['id']
                            );
                            ?>"
                            class="edit-btn"
                        >
                            ✏️ Edit
                        </a>


                        <a
                            href="delete_user.php?id=<?php
                            echo htmlspecialchars(
                                $row['id']
                            );
                            ?>"
                            class="delete-btn"
                            onclick="return confirm('Are you sure you want to delete this user?')"
                        >
                            🗑️ Delete
                        </a>


                    </div>

                </td>


            </tr>


            <?php

            }

            ?>


        </table>

        </div>


        <?php

        }
        else{

        ?>


            <div class="empty-box">

                <div class="empty-icon">
                    👥
                </div>

                <h3>
                    No Users Found
                </h3>

                <p>
                    There are currently no registered users.
                </p>

            </div>


        <?php

        }

        ?>


        <div class="users-bottom">

            <a href="dashboard.php">
                ← Return to Admin Dashboard
            </a>

        </div>


    </div>


</div>

</div>

</body>

</html>