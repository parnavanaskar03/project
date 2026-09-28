<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['submit'])){

    $message = mysqli_real_escape_string($conn,$_POST['message']);
    $rating = $_POST['rating'];

    $sql = "INSERT INTO feedback(user_id,message,rating)
    VALUES('$user_id','$message','$rating')";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Feedback Submitted Successfully');
        window.location='feedback.php';
        </script>";

    }else{

        echo "<script>
        alert('Submission Failed');
        </script>";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Feedback - Campus Care</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="feedback-page">

    <div class="feedback-container">

        <!-- HEADER -->

        <div class="feedback-header">

            <div>

                <h1>⭐ Student Feedback</h1>

                <p>
                    Your feedback helps us improve Campus Care.
                </p>

            </div>

            <a
                href="../student_dashboard.php"
                class="feedback-back">

                ← Dashboard

            </a>

        </div>


        <!-- MAIN CARD -->

        <div class="feedback-card">

            <div class="feedback-title">

                <div class="feedback-icon">
                    💬
                </div>

                <div>

                    <h2>Share Your Feedback</h2>

                    <p>
                        Tell us about your experience with Campus Care.
                    </p>

                </div>

            </div>


            <form method="POST">

                <!-- RATING -->

                <div class="feedback-group">

                    <label>How would you rate your experience?</label>

                    <select name="rating" required>

                        <option value="">
                            Select Rating
                        </option>

                        <option value="5">
                            ⭐⭐⭐⭐⭐ Excellent
                        </option>

                        <option value="4">
                            ⭐⭐⭐⭐ Good
                        </option>

                        <option value="3">
                            ⭐⭐⭐ Average
                        </option>

                        <option value="2">
                            ⭐⭐ Poor
                        </option>

                        <option value="1">
                            ⭐ Very Poor
                        </option>

                    </select>

                </div>


                <!-- MESSAGE -->

                <div class="feedback-group">

                    <label>Your Feedback</label>

                    <textarea
                        name="message"
                        rows="6"
                        placeholder="Write your feedback here..."
                        required></textarea>

                </div>


                <!-- INFO -->

                <div class="feedback-info">

                    <strong>💡 Your opinion matters!</strong>

                    <p>
                        Honest feedback helps the college improve
                        services and make Campus Care better for students.
                    </p>

                </div>


                <!-- BUTTONS -->

                <div class="feedback-buttons">

                    <a
                        href="../student_dashboard.php"
                        class="feedback-cancel">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        name="submit"
                        class="feedback-submit">

                        ⭐ Submit Feedback

                    </button>

                </div>

            </form>

        </div>


        <!-- FEEDBACK POINTS -->

        <div class="feedback-points">

            <h3>What can you give feedback about?</h3>

            <div class="feedback-options">

                <div>
                    📝
                    <strong>Complaints</strong>
                    <span>Complaint handling experience</span>
                </div>

                <div>
                    📢
                    <strong>Notice Board</strong>
                    <span>Information and announcements</span>
                </div>

                <div>
                    🔎
                    <strong>Lost & Found</strong>
                    <span>Lost item service</span>
                </div>

                <div>
                    💻
                    <strong>Campus Care</strong>
                    <span>Overall platform experience</span>
                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>