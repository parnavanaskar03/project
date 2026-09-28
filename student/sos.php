<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['send'])){

    $user_id = $_SESSION['user_id'];
    $location = $_POST['location'];
    $message = $_POST['message'];

    $sql = "INSERT INTO sos_requests
    (user_id, location, message, status)
    VALUES
    ('$user_id','$location','$message','Pending')";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Emergency SOS Sent Successfully');
        window.location='../student_dashboard.php';
        </script>";

    }else{

        echo "<script>
        alert('Failed to Send SOS');
        </script>";

    }

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Emergency SOS - Campus Care</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="emergency-page">

    <div class="emergency-container">

        <!-- HEADER -->

        <div class="emergency-header">

            <div class="emergency-symbol">
                🚨
            </div>

            <h1>Emergency SOS</h1>

            <p>
                Use this only when you need immediate campus assistance.
            </p>

        </div>


        <!-- SOS CARD -->

        <div class="emergency-card">

            <div class="emergency-alert">

                <strong>⚠️ Emergency Assistance</strong>

                <p>
                    Enter your current location and briefly describe
                    the emergency. Your request will be sent to the
                    campus support team.
                </p>

            </div>


            <form method="POST">

                <!-- LOCATION -->

                <div class="emergency-group">

                    <label>📍 Current Location</label>

                    <input
                        type="text"
                        name="location"
                        placeholder="Example: Hostel Block A, Room 205"
                        required>

                    <small>
                        Enter the place where assistance is needed.
                    </small>

                </div>


                <!-- MESSAGE -->

                <div class="emergency-group">

                    <label>🚨 Describe Your Emergency</label>

                    <textarea
                        name="message"
                        rows="6"
                        placeholder="Briefly describe what happened and what help you need..."
                        required></textarea>

                </div>


                <!-- IMPORTANT -->

                <div class="emergency-note">

                    <span>🛡️</span>

                    <div>

                        <strong>Important</strong>

                        <p>
                            Please provide correct information.
                            False or unnecessary emergency requests
                            should not be submitted.
                        </p>

                    </div>

                </div>


                <!-- BUTTONS -->

                <div class="emergency-buttons">

                    <a
                        href="../student_dashboard.php"
                        class="emergency-cancel">

                        ← Cancel

                    </a>

                    <button
                        type="submit"
                        name="send"
                        class="emergency-send">

                        🚨 Send Emergency SOS

                    </button>

                </div>

            </form>

        </div>


        <!-- PROCESS -->

        <div class="emergency-process">

            <h3>What happens after sending SOS?</h3>

            <div class="emergency-steps">

                <div class="emergency-step">

                    <div class="emergency-number">1</div>

                    <strong>SOS Sent</strong>

                    <p>Your emergency request is recorded.</p>

                </div>


                <div class="emergency-step">

                    <div class="emergency-number">2</div>

                    <strong>Request Reviewed</strong>

                    <p>Campus staff checks your request.</p>

                </div>


                <div class="emergency-step">

                    <div class="emergency-number">3</div>

                    <strong>Assistance</strong>

                    <p>Appropriate support is contacted.</p>

                </div>

            </div>

        </div>


        <!-- BACK -->

        <div class="emergency-bottom">

            <a href="../student_dashboard.php">
                ← Back to Dashboard
            </a>

            <a href="my_sos.php">
                View My SOS Status →
            </a>

        </div>

    </div>

</div>

</body>

</html>