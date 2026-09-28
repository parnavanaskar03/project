<?php

session_start();

include("config/db.php");

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check email from database

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE email='$email'"
    );

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        // Check password

        if(password_verify($password, $user['password'])){

            // Check selected role

            if($role != $user['role']){

                echo "<script>
                alert('Wrong Login Type!');
                </script>";

            } else {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role

                if($user['role']=="student"){

                    header("Location: student_dashboard.php");
                    exit();

                }
                else if($user['role']=="teacher"){

                    header("Location: teacher/dashboard.php");
                    exit();

                }
                else if($user['role']=="hod"){

                    header("Location: hod/dashboard.php");
                    exit();

                }
                else if($user['role']=="warden"){

                    header("Location: warden/dashboard.php");
                    exit();

                }
                else if($user['role']=="electrician"){

                    header("Location: electrician/dashboard.php");
                    exit();

                }
                else if($user['role']=="security"){

                    header("Location: security/dashboard.php");
                    exit();

                }
                else if($user['role']=="admin"){

                    header("Location: admin/dashboard.php");
                    exit();

                }

            }

        } else {

            echo "<script>
            alert('Wrong Password!');
            </script>";

        }

    } else {

        echo "<script>
        alert('Email Not Found!');
        </script>";

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Login</title>

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
    padding:25px 15px;
}

/* MAIN BOX */

.login-wrapper{
    width:100%;
    max-width:1000px;
    min-height:600px;
    background:white;
    border-radius:22px;
    overflow:hidden;
    display:flex;
    box-shadow:0 15px 40px rgba(23,37,84,0.12);
}

/* LEFT SIDE */

.login-left{
    width:45%;
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

.login-left h1{
    font-size:30px;
    margin-bottom:12px;
}

.login-left .subtitle{
    color:#bfdbfe;
    font-size:14px;
    line-height:1.6;
    margin-bottom:35px;
}

/* FEATURES */

.features{
    text-align:left;
    display:flex;
    flex-direction:column;
    gap:14px;
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

.login-right{
    width:55%;
    padding:50px 60px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.login-heading{
    margin-bottom:28px;
}

.login-heading h2{
    color:#172554;
    font-size:28px;
    margin-bottom:7px;
}

.login-heading p{
    color:#64748b;
    font-size:13px;
}

/* INFO */

.login-info{
    background:#eff6ff;
    border-left:4px solid #2563eb;
    padding:13px 15px;
    border-radius:7px;
    margin-bottom:22px;
}

.login-info p{
    color:#475569;
    font-size:11px;
    line-height:1.5;
}

/* FORM */

.form-group{
    margin-bottom:20px;
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
    height:47px;
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

/* LOGIN BUTTON */

.login-btn{
    width:100%;
    height:48px;
    border:none;
    border-radius:9px;
    background:#2563eb;
    color:white;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
    margin-top:3px;
    transition:0.2s;
}

.login-btn:hover{
    background:#1d4ed8;
}

/* REGISTER LINK */

.register-link{
    text-align:center;
    margin-top:22px;
    color:#64748b;
    font-size:13px;
}

.register-link a{
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}

.register-link a:hover{
    text-decoration:underline;
}

/* BACK HOME */

.back-home{
    text-align:center;
    margin-top:13px;
}

.back-home a{
    color:#64748b;
    text-decoration:none;
    font-size:12px;
}

.back-home a:hover{
    color:#2563eb;
}

/* MOBILE */

@media(max-width:800px){

    .login-wrapper{
        flex-direction:column;
    }

    .login-left,
    .login-right{
        width:100%;
    }

    .login-left{
        padding:35px 25px;
    }

    .login-left h1{
        font-size:25px;
    }

    .features{
        display:none;
    }

    .login-right{
        padding:35px 25px;
    }

}

@media(max-width:550px){

    body{
        padding:15px 10px;
    }

    .login-wrapper{
        border-radius:15px;
    }

    .login-left{
        padding:30px 20px;
    }

    .login-right{
        padding:30px 20px;
    }

    .login-heading h2{
        font-size:23px;
    }

}

</style>

</head>

<body>

<div class="login-wrapper">


    <!-- LEFT SIDE -->

    <div class="login-left">

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

                <div class="feature-icon">
                    📋
                </div>

                <span>
                    Smart Complaint Management
                </span>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    📢
                </div>

                <span>
                    Campus Notice Board
                </span>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    🔎
                </div>

                <span>
                    Lost & Found Service
                </span>

            </div>


            <div class="feature">

                <div class="feature-icon">
                    🚨
                </div>

                <span>
                    Emergency SOS Support
                </span>

            </div>

        </div>

    </div>


    <!-- RIGHT SIDE -->

    <div class="login-right">


        <div class="login-heading">

            <h2>Welcome Back</h2>

            <p>
                Login to access your Campus Care account
            </p>

        </div>


        <div class="login-info">

            <p>
                Enter your registered email, password and select
                the correct account type to continue.
            </p>

        </div>


        <form method="POST">


            <!-- EMAIL -->

            <div class="form-group">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email address"
                    required
                >

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <!-- ROLE -->

            <div class="form-group">

                <label>Login As</label>

                <select name="role" required>

                    <option value="">
                        Select your role
                    </option>

                    <option value="student">
                        Student
                    </option>

                    <option value="teacher">
                        Teacher
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

                    <option value="admin">
                        Admin
                    </option>

                </select>

            </div>


            <!-- LOGIN -->

            <button
                type="submit"
                name="login"
                class="login-btn"
            >
                Login to Campus Care
            </button>


        </form>


        <!-- REGISTER -->

        <div class="register-link">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </div>


        <!-- HOME -->

        <div class="back-home">

            <a href="index.php">
                ← Back to Home
            </a>

        </div>


    </div>

</div>

</body>

</html>