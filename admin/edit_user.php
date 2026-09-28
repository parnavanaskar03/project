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

if(!isset($_GET['id'])){
    header("Location: manage_users.php");
    exit();
}

$id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE id='$id'");
$user = mysqli_fetch_assoc($query);

if(!$user){
    header("Location: manage_users.php");
    exit();
}

if(isset($_POST['update'])){

    $fullname = $_POST['fullname'];
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $role = $_POST['role'];

    mysqli_query($conn,"
    UPDATE users SET
    fullname='$fullname',
    student_id='$student_id',
    email='$email',
    phone='$phone',
    department='$department',
    role='$role'
    WHERE id='$id'
    ");

    echo "<script>
    alert('User Updated Successfully');
    window.location='manage_users.php';
    </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit User - Campus Care</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, sans-serif;
}

body{
    background:#f4f7fb;
    min-height:100vh;
}

/* TOP BAR */

.topbar{
    height:70px;
    background:#172554;
    color:white;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 35px;
}

.topbar h2{
    font-size:21px;
}

.topbar p{
    font-size:13px;
    color:#bfdbfe;
}

/* PAGE */

.page{
    max-width:900px;
    margin:35px auto;
    padding:0 20px;
}

/* HEADER */

.page-header{
    margin-bottom:25px;
}

.page-header h1{
    color:#172554;
    font-size:28px;
    margin-bottom:7px;
}

.page-header p{
    color:#64748b;
    font-size:14px;
}

/* CARD */

.edit-card{
    background:white;
    border-radius:18px;
    padding:30px;
    box-shadow:0 8px 25px rgba(0,0,0,0.07);
}

/* USER HEADER */

.user-header{
    display:flex;
    align-items:center;
    gap:18px;
    padding-bottom:25px;
    margin-bottom:25px;
    border-bottom:1px solid #e5e7eb;
}

.user-photo{
    width:80px;
    height:80px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #dbeafe;
}

.default-photo{
    width:80px;
    height:80px;
    border-radius:50%;
    background:#2563eb;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    font-weight:bold;
}

.user-header h2{
    color:#172554;
    font-size:20px;
}

.user-header p{
    color:#64748b;
    font-size:13px;
    margin-top:5px;
}

/* FORM */

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.form-group{
    margin-bottom:20px;
}

.form-group.full{
    grid-column:1 / -1;
}

.form-group label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:600;
    margin-bottom:8px;
}

.form-group input,
.form-group select{
    width:100%;
    padding:13px 14px;
    border:1px solid #dbe3ef;
    border-radius:9px;
    outline:none;
    font-size:14px;
    background:#fff;
    transition:0.2s;
}

.form-group input:focus,
.form-group select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,0.10);
}

/* USER ID */

.user-id-box{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    padding:12px 14px;
    border-radius:9px;
    color:#475569;
    font-size:14px;
}

/* ROLE */

.role-info{
    background:#eff6ff;
    border-left:4px solid #2563eb;
    padding:12px 14px;
    border-radius:7px;
    color:#475569;
    font-size:12px;
    margin-bottom:22px;
}

/* BUTTONS */

.form-buttons{
    display:flex;
    gap:12px;
    margin-top:10px;
    padding-top:22px;
    border-top:1px solid #e5e7eb;
}

.update-btn{
    border:none;
    background:#2563eb;
    color:white;
    padding:13px 25px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
}

.update-btn:hover{
    background:#1d4ed8;
}

.cancel-btn{
    text-decoration:none;
    background:#f1f5f9;
    color:#334155;
    padding:13px 22px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
}

.cancel-btn:hover{
    background:#e2e8f0;
}

/* RESPONSIVE */

@media(max-width:650px){

    .topbar{
        padding:0 18px;
    }

    .topbar h2{
        font-size:18px;
    }

    .page{
        margin-top:25px;
    }

    .edit-card{
        padding:22px;
    }

    .form-row{
        grid-template-columns:1fr;
        gap:0;
    }

    .form-group.full{
        grid-column:auto;
    }

    .user-header{
        align-items:flex-start;
    }

    .form-buttons{
        flex-direction:column;
    }

    .update-btn,
    .cancel-btn{
        text-align:center;
        width:100%;
    }

}

</style>

</head>

<body>

<!-- TOP BAR -->

<div class="topbar">

    <div>
        <h2>Campus Care</h2>
        <p>Admin Panel</p>
    </div>

    <div>
        <p>✏️ Edit User</p>
    </div>

</div>


<!-- PAGE -->

<div class="page">

    <div class="page-header">

        <h1>Edit User</h1>

        <p>
            Update user information and account role.
        </p>

    </div>


    <div class="edit-card">


        <!-- USER HEADER -->

        <div class="user-header">

            <?php

            if(!empty($user['profile_pic'])){

                echo "<img
                src='../uploads/".$user['profile_pic']."'
                class='user-photo'>";

            }else{

                $first = strtoupper(substr($user['fullname'],0,1));

                echo "<div class='default-photo'>
                ".$first."
                </div>";

            }

            ?>

            <div>

                <h2>
                    <?php echo htmlspecialchars($user['fullname']); ?>
                </h2>

                <p>
                    User ID: #<?php echo $user['id']; ?>
                </p>

            </div>

        </div>


        <!-- FORM -->

        <form method="POST">


            <div class="form-row">


                <!-- FULL NAME -->

                <div class="form-group">

                    <label>Full Name</label>

                    <input
                    type="text"
                    name="fullname"
                    value="<?php echo htmlspecialchars($user['fullname']); ?>"
                    required>

                </div>


                <!-- STUDENT ID -->

                <div class="form-group">

                    <label>Student ID</label>

                    <input
                    type="text"
                    name="student_id"
                    value="<?php echo htmlspecialchars($user['student_id']); ?>"
                    required>

                </div>


                <!-- EMAIL -->

                <div class="form-group">

                    <label>Email Address</label>

                    <input
                    type="email"
                    name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>"
                    required>

                </div>


                <!-- PHONE -->

                <div class="form-group">

                    <label>Phone Number</label>

                    <input
                    type="text"
                    name="phone"
                    value="<?php echo htmlspecialchars($user['phone']); ?>"
                    required>

                </div>


                <!-- DEPARTMENT -->

                <div class="form-group">

                    <label>Department</label>

                    <input
                    type="text"
                    name="department"
                    value="<?php echo htmlspecialchars($user['department']); ?>"
                    placeholder="Enter Department">

                </div>


                <!-- ROLE -->

                <div class="form-group">

                    <label>User Role</label>

                    <select name="role" required>

                        <option value="student"
                        <?php if($user['role']=="student") echo "selected"; ?>>
                        Student
                        </option>

                        <option value="teacher"
                        <?php if($user['role']=="teacher") echo "selected"; ?>>
                        Teacher
                        </option>

                        <option value="hod"
                        <?php if($user['role']=="hod") echo "selected"; ?>>
                        HOD
                        </option>

                        <option value="warden"
                        <?php if($user['role']=="warden") echo "selected"; ?>>
                        Warden
                        </option>

                        <option value="electrician"
                        <?php if($user['role']=="electrician") echo "selected"; ?>>
                        Electrician
                        </option>

                        <option value="security"
                        <?php if($user['role']=="security") echo "selected"; ?>>
                        Security
                        </option>

                        <option value="admin"
                        <?php if($user['role']=="admin") echo "selected"; ?>>
                        Admin
                        </option>

                    </select>

                </div>

            </div>


            <div class="role-info">

                ⚠️ Changing the role will change which dashboard
                and features this user can access after login.

            </div>


            <!-- BUTTONS -->

            <div class="form-buttons">

                <button
                type="submit"
                name="update"
                class="update-btn">
                    ✓ Update User
                </button>

                <a
                href="manage_users.php"
                class="cancel-btn">
                    ← Back to Users
                </a>

            </div>


        </form>

    </div>

</div>

</body>

</html>