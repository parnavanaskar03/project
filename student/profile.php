<?php
session_start();

include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$message = "";
$error = "";

/* =========================
   UPDATE PROFILE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $department = trim($_POST['department']);

    if ($fullname == "") {
        $error = "Full name cannot be empty.";
    } else {

        $profile_pic = null;

        /* Profile picture upload */
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {

            $upload_dir = "../uploads/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $file_name = $_FILES['profile_pic']['name'];
            $tmp_name = $_FILES['profile_pic']['tmp_name'];
            $file_size = $_FILES['profile_pic']['size'];

            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed = ["jpg", "jpeg", "png", "webp"];

            if (!in_array($extension, $allowed)) {

                $error = "Only JPG, JPEG, PNG and WEBP images are allowed.";

            } elseif ($file_size > 5 * 1024 * 1024) {

                $error = "Image size must be less than 5 MB.";

            } else {

                $new_name = "profile_" . $user_id . "_" . time() . "." . $extension;

                $destination = $upload_dir . $new_name;

                if (move_uploaded_file($tmp_name, $destination)) {
                    $profile_pic = $new_name;
                } else {
                    $error = "Failed to upload profile picture.";
                }
            }
        }

        /* Update database */
        if ($error == "") {

            if ($profile_pic != null) {

                $stmt = mysqli_prepare(
                    $conn,
                    "UPDATE users 
                     SET fullname = ?, phone = ?, department = ?, profile_pic = ?
                     WHERE id = ?"
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssi",
                    $fullname,
                    $phone,
                    $department,
                    $profile_pic,
                    $user_id
                );

            } else {

                $stmt = mysqli_prepare(
                    $conn,
                    "UPDATE users 
                     SET fullname = ?, phone = ?, department = ?
                     WHERE id = ?"
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssi",
                    $fullname,
                    $phone,
                    $department,
                    $user_id
                );
            }

            if (mysqli_stmt_execute($stmt)) {
                $message = "Profile updated successfully.";
            } else {
                $error = "Failed to update profile.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}


/* =========================
   GET USER DATA
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    die("User profile not found.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Profile - Campus Care</title>

<style>

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f7fb;
    color: #222;
}

/* HEADER */

.header {
    background: #123b69;
    color: white;
    padding: 18px 30px;
    font-size: 22px;
    font-weight: bold;
}

/* MAIN */

.container {
    max-width: 850px;
    margin: 35px auto;
    padding: 20px;
}

/* PROFILE CARD */

.profile-card {
    background: white;
    border-radius: 15px;
    padding: 35px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

/* TITLE */

.title {
    text-align: center;
    margin-bottom: 30px;
}

.title h2 {
    margin: 10px 0 5px;
    color: #123b69;
}

.title p {
    margin: 0;
    color: #777;
}

/* PROFILE IMAGE */

.profile-image {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    margin: auto;
    overflow: hidden;
    background: #123b69;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 45px;
    border: 4px solid #e5edf7;
}

.profile-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* FORM */

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #444;
}

.form-group input {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #d5dce5;
    border-radius: 8px;
    font-size: 15px;
    outline: none;
}

.form-group input:focus {
    border-color: #123b69;
}

/* READ ONLY */

.readonly {
    background: #f1f3f6;
    color: #777;
    cursor: not-allowed;
}

/* BUTTONS */

.buttons {
    display: flex;
    gap: 12px;
    margin-top: 25px;
}

.save-btn {
    border: none;
    background: #123b69;
    color: white;
    padding: 12px 22px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
}

.save-btn:hover {
    background: #0d2d50;
}

.cancel-btn {
    background: #e9edf2;
    color: #333;
    padding: 12px 22px;
    border-radius: 8px;
    text-decoration: none;
}

.cancel-btn:hover {
    background: #dce2e8;
}

/* MESSAGE */

.success {
    background: #dff5e5;
    color: #217a3b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.error {
    background: #ffe1e1;
    color: #b32626;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
}

/* PHOTO */

.photo-note {
    font-size: 13px;
    color: #777;
    margin-top: 6px;
}

/* RESPONSIVE */

@media(max-width: 600px) {

    .container {
        margin: 15px auto;
        padding: 10px;
    }

    .profile-card {
        padding: 25px 20px;
    }

    .buttons {
        flex-direction: column;
    }

    .save-btn,
    .cancel-btn {
        text-align: center;
        width: 100%;
    }
}

</style>

</head>

<body>


<div class="header">
    Campus Care
</div>


<div class="container">

<div class="profile-card">

    <div class="title">

        <div class="profile-image">

            <?php if (!empty($user['profile_pic'])): ?>

                <img
                    src="../uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>"
                    alt="Profile Picture"
                >

            <?php else: ?>

                👤

            <?php endif; ?>

        </div>

        <h2>
            <?php echo htmlspecialchars($user['fullname']); ?>
        </h2>

        <p>
            <?php echo htmlspecialchars($user['role']); ?>
        </p>

    </div>


    <?php if ($message != ""): ?>

        <div class="success">
            ✓ <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>


    <?php if ($error != ""): ?>

        <div class="error">
            ✕ <?php echo htmlspecialchars($error); ?>
        </div>

    <?php endif; ?>


    <form method="POST" enctype="multipart/form-data">


        <!-- FULL NAME -->

        <div class="form-group">

            <label>Full Name</label>

            <input
                type="text"
                name="fullname"
                value="<?php echo htmlspecialchars($user['fullname']); ?>"
                required
            >

        </div>


        <!-- STUDENT ID -->

        <div class="form-group">

            <label>Student ID</label>

            <input
                type="text"
                value="<?php echo htmlspecialchars($user['student_id']); ?>"
                class="readonly"
                readonly
            >

        </div>


        <!-- EMAIL -->

        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                value="<?php echo htmlspecialchars($user['email']); ?>"
                class="readonly"
                readonly
            >

        </div>


        <!-- PHONE -->

        <div class="form-group">

            <label>Phone Number</label>

            <input
                type="text"
                name="phone"
                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                placeholder="Enter phone number"
            >

        </div>


        <!-- DEPARTMENT -->

        <div class="form-group">

            <label>Department</label>

            <input
                type="text"
                name="department"
                value="<?php echo htmlspecialchars($user['department'] ?? ''); ?>"
                placeholder="Enter department"
            >

        </div>


        <!-- ROLE -->

        <div class="form-group">

            <label>Role</label>

            <input
                type="text"
                value="<?php echo htmlspecialchars($user['role']); ?>"
                class="readonly"
                readonly
            >

        </div>


        <!-- PROFILE PICTURE -->

        <div class="form-group">

            <label>Change Profile Picture</label>

            <input
                type="file"
                name="profile_pic"
                accept=".jpg,.jpeg,.png,.webp"
            >

            <div class="photo-note">
                JPG, JPEG, PNG or WEBP • Maximum 5 MB
            </div>

        </div>


        <!-- BUTTONS -->

        <div class="buttons">

    <button type="submit" class="save-btn">
        💾 Save Changes
    </button>

    <a href="../student_dashboard.php" class="cancel-btn">
        ← Back to Dashboard
    </a>

</div>


    </form>

</div>

</div>

</body>

</html>