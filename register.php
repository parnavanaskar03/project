<?php

include("config/db.php");

if(isset($_POST['register'])){

    $fullname = $_POST['fullname'];
    $student_id = $_POST['student_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check duplicate Email or Student ID

    $check = mysqli_query(
        $conn,
        "SELECT * FROM users 
         WHERE email='$email' OR student_id='$student_id'"
    );

    if(mysqli_num_rows($check) > 0){

        echo "<script>
        alert('Email or Student ID Already Exists!');
        </script>";

    } else {

        $sql = "INSERT INTO users
        (fullname, student_id, email, phone, password, role)
        VALUES
        ('$fullname','$student_id','$email','$phone','$password','$role')";

        if(mysqli_query($conn,$sql)){

            echo "<script>
            alert('Registration Successful!');
            window.location='login.php';
            </script>";

        } else {

            echo "<script>
            alert('Registration Failed!');
            </script>";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Registration</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    min-height:100vh;
    background:#eef4ff;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:30px 15px;
}

/* MAIN CONTAINER */

.register-wrapper{
    width:100%;
    max-width:1050px;
    min-height:650px;
    background:white;
    border-radius:22px;
    overflow:hidden;
    display:flex;
    box-shadow:0 15px 40px rgba(23,37,84,0.12);
}

/* LEFT SIDE */

.register-left{
    width:42%;
    background:#172554;
    color:white;
    padding:50px 40px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    text-align:center;
}

.logo-icon{
    width:75px;
    height:75px;
    margin:0 auto 20px;
    background:#2563eb;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:35px;
}

.register-left h1{
    font-size:30px;
    margin-bottom:12px;
}

.register-left .subtitle{
    color:#bfdbfe;
    font-size:14px;
    line-height:1.6;
    margin-bottom:35px;
}

.features{
    text-align:left;
    display:flex;
    flex-direction:column;
    gap:15px;
}

.feature{
    display:flex;
    align-items:center;
    gap:12px;
    background:rgba(255,255,255,0.08);
    padding:13px 15px;
    border-radius:10px;
}

.feature-icon{
    width:35px;
    height:35px;
    border-radius:8px;
    background:#2563eb;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:17px;
}

.feature span{
    font-size:13px;
    color:#e0e7ff;
}

/* RIGHT SIDE */

.register-right{
    width:58%;
    padding:45px 55px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.register-heading{
    margin-bottom:28px;
}

.register-heading h2{
    color:#172554;
    font-size:27px;
    margin-bottom:7px;
}

.register-heading p{
    color:#64748b;
    font-size:13px;
}

/* FORM */

.form-row{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:17px;
}

.form-group{
    margin-bottom:18px;
}

.form-group label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:600;
    margin-bottom:7px;
}

.form-control,
select{
    width:100%;
    height:46px;
    border:1px solid #d7dee8;
    border-radius:9px;
    padding:0 13px;
    font-size:13px;
    color:#334155;
    background:white;
    outline:none;
}

.form-control:focus,
select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,0.10);
}

/* REGISTER BUTTON */

.btn-register{
    width:100%;
    height:47px;
    border:none;
    border-radius:9px;
    background:#2563eb;
    color:white;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    margin-top:5px;
    transition:0.2s;
}

.btn-register:hover{
    background:#1d4ed8;
}

/* LOGIN LINK */

.login-link{
    text-align:center;
    margin-top:20px;
    font-size:13px;
    color:#64748b;
}

.login-link a{
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}

.login-link a:hover{
    text-decoration:underline;
}

/* NOTE */

.form-note{
    background:#eff6ff;
    border-left:4px solid #2563eb;
    padding:12px 14px;
    border-radius:7px;
    margin-bottom:20px;
}

.form-note p{
    color:#475569;
    font-size:11px;
    line-height:1.5;
}

/* MOBILE */

@media(max-width:800px){

    .register-wrapper{
        flex-direction:column;
    }

    .register-left,
    .register-right{
        width:100%;
    }

    .register-left{
        padding:35px 25px;
    }

    .register-left h1{
        font-size:25px;
    }

    .features{
        display:none;
    }

    .register-right{
        padding:35px 25px;
    }
}

@media(max-width:550px){

    body{
        padding:15px 10px;
    }

    .register-wrapper{
        border-radius:15px;
    }

    .form-row{
        grid-template-columns:1fr;
        gap:0;
    }

    .register-heading h2{
        font-size:23px;
    }

    .register-left{
        padding:30px 20px;
    }

    .register-right{
        padding:30px 20px;
    }
}

</style>

</head>

<body>

<div class="register-wrapper">

    <!-- LEFT SIDE -->

    <div class="register-left">

        <div class="logo-icon">
            🏫
        </div>

        <h1>Campus Care</h1>

        <p class="subtitle">
            Smart Campus Management System
            <br>
            Connect. Report. Resolve.
        </p>

        <div class="features">

            <div class="feature">
                <div class="feature-icon">📋</div>
                <span>Smart Complaint Management</span>
            </div>

            <div class="feature">
                <div class="feature-icon">📢</div>
                <span>Campus Notice Board</span>
            </div>

            <div class="feature">
                <div class="feature-icon">🔎</div>
                <span>Lost & Found Service</span>
            </div>

            <div class="feature">
                <div class="feature-icon">🚨</div>
                <span>Emergency SOS Support</span>
            </div>

        </div>

    </div>


    <!-- RIGHT SIDE -->

    <div class="register-right">

        <div class="register-heading">

            <h2>Create Your Account</h2>

            <p>
                Register to access the Campus Care system
            </p>

        </div>


        <div class="form-note">

            <p>
                Please enter your correct information. Your Student ID
                and Email ID will be used for account verification.
            </p>

        </div>


        <form method="POST">


            <!-- FULL NAME + STUDENT ID -->

            <div class="form-row">

                <div class="form-group">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="fullname"
                        class="form-control"
                        placeholder="Enter your full name"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Student ID</label>

                    <input
                        type="text"
                        name="student_id"
                        class="form-control"
                        placeholder="Enter Student ID"
                        required
                    >

                </div>

            </div>


            <!-- EMAIL + PHONE -->

            <div class="form-row">

                <div class="form-group">

                    <label>Email ID</label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Enter email address"
                        required
                    >

                </div>


                <div class="form-group">

                    <label>Phone Number</label>

                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        placeholder="Enter phone number"
                        required
                    >

                </div>

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Create a password"
                    required
                >

            </div>


            <!-- ROLE -->

            <div class="form-group">

                <label>Select Role</label>

                <select name="role" required>

                    <option value="">Select your role</option>

                    <option value="student">Student</option>

                    <option value="teacher">Teacher</option>

                    <option value="hod">HOD</option>

                    <option value="warden">Warden</option>

                    <option value="electrician">Electrician</option>

                    <option value="security">Security</option>

                    <option value="admin">Admin</option>

                </select>

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="register"
                class="btn-register"
            >
                Create Account
            </button>


        </form>


        <div class="login-link">

            Already have an account?

            <a href="login.php">
                Login here
            </a>

        </div>

    </div>

</div>

</body>

</html>