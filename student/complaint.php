<?php

session_start();

include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['submit'])){

    $user_id = $_SESSION['user_id'];

    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);

    $image = "";

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){

        $image = time() . "_" . basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../uploads/" . $image
        );
    }

    $sql = "INSERT INTO complaints
            (user_id,title,category,description,image)
            VALUES
            ('$user_id','$title','$category','$description','$image')";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Complaint Submitted Successfully');
        window.location='../student_dashboard.php';
        </script>";

    }else{

        echo "<script>
        alert('Submission Failed');
        </script>";
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>New Complaint - Campus Care</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="complaint-page">

    <div class="complaint-container">

        <!-- HEADER -->

        <div class="complaint-header">

            <h1>📝 New Complaint</h1>

            <p>
                Report a campus problem and let the administration know.
            </p>

        </div>


        <!-- FORM CARD -->

        <div class="complaint-card">

            <div class="complaint-info">

                <strong>📢 Before submitting:</strong>

                Please provide correct information about the problem.
                Your complaint will be reviewed by the administration
                and assigned to the appropriate department.

            </div>


            <form method="POST" enctype="multipart/form-data">


                <!-- TITLE -->

                <div class="form-group">

                    <label>
                        Complaint Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Example: Classroom fan is not working"
                        required
                    >

                </div>


                <!-- CATEGORY + IMAGE -->

                <div class="form-row">

                    <div class="form-group">

                        <label>
                            Complaint Category
                        </label>

                        <select name="category" required>

                            <option value="">
                                Select Category
                            </option>

                            <option value="Electrical">
                                ⚡ Electrical
                            </option>

                            <option value="Water">
                                💧 Water
                            </option>

                            <option value="Internet">
                                🌐 Internet
                            </option>

                            <option value="Hostel">
                                🏠 Hostel
                            </option>

                            <option value="Library">
                                📚 Library
                            </option>

                            <option value="Classroom">
                                🏫 Classroom
                            </option>

                            <option value="Washroom">
                                🚿 Washroom
                            </option>

                            <option value="Others">
                                📌 Others
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>
                            Upload Image
                        </label>

                        <div class="file-box">

                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                            >

                            <div class="file-note">
                                Optional • Add a photo if it helps explain the problem.
                            </div>

                        </div>

                    </div>

                </div>


                <!-- DESCRIPTION -->

                <div class="form-group">

                    <label>
                        Problem Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Describe the problem clearly..."
                        required
                    ></textarea>

                </div>


                <!-- BUTTONS -->

                <div class="form-buttons">

                    <button
                        type="submit"
                        name="submit"
                        class="submit-complaint"
                    >
                        📤 Submit Complaint
                    </button>


                    <a
                        href="../student_dashboard.php"
                        class="back-dashboard"
                    >
                        ← Back to Dashboard
                    </a>

                </div>

            </form>

        </div>


        <!-- PROCESS -->

        <div class="process-box">

            <h3>
                📋 Complaint Process
            </h3>

            <div class="process-steps">

                <div class="process-step">

                    <div class="process-number">
                        1
                    </div>

                    <strong>Submitted</strong>

                </div>


                <div class="process-step">

                    <div class="process-number">
                        2
                    </div>

                    <strong>Admin Review</strong>

                </div>


                <div class="process-step">

                    <div class="process-number">
                        3
                    </div>

                    <strong>Assigned</strong>

                </div>


                <div class="process-step">

                    <div class="process-number">
                        4
                    </div>

                    <strong>In Progress</strong>

                </div>


                <div class="process-step">

                    <div class="process-number">
                        5
                    </div>

                    <strong>Resolved</strong>

                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>