<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['submit'])){

    $user_id = $_SESSION['user_id'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    $image = $_FILES['image']['name'];
    $temp = $_FILES['image']['tmp_name'];

    move_uploaded_file($temp, "../uploads/".$image);

    $sql = "INSERT INTO lost_found
    (user_id,item_name,description,location,image,status)
    VALUES
    ('$user_id','$item_name','$description','$location','$image','Lost')";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Lost Item Submitted Successfully');
        window.location='lost_found.php';
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

<title>Lost & Found - Campus Care</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="lost-page">

    <div class="lost-container">

        <!-- HEADER -->

        <div class="lost-header">

            <div>
                <h1>🔎 Lost & Found</h1>
                <p>Report a lost item and help bring it back safely.</p>
            </div>

            <a href="../student_dashboard.php" class="lost-back">
                ← Dashboard
            </a>

        </div>


        <!-- MAIN CARD -->

        <div class="lost-card">

            <div class="lost-card-title">

                <div class="lost-icon">
                    🔍
                </div>

                <div>
                    <h2>Report Lost Item</h2>
                    <p>Please provide the details of the lost item.</p>
                </div>

            </div>


            <form method="POST" enctype="multipart/form-data">

                <!-- ITEM NAME -->

                <div class="lost-form-group">

                    <label>Item Name</label>

                    <input
                        type="text"
                        name="item_name"
                        placeholder="Example: Black Wallet"
                        required>

                </div>


                <!-- LOCATION -->

                <div class="lost-form-group">

                    <label>Last Seen Location</label>

                    <input
                        type="text"
                        name="location"
                        placeholder="Example: Library, Block A"
                        required>

                </div>


                <!-- DESCRIPTION -->

                <div class="lost-form-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Describe the item, colour, brand or any other details..."
                        required></textarea>

                </div>


                <!-- IMAGE -->

                <div class="lost-form-group">

                    <label>Upload Item Image</label>

                    <div class="lost-file-box">

                        <div class="upload-icon">
                            📷
                        </div>

                        <div>

                            <strong>Choose an image</strong>

                            <p>
                                Upload a clear photo of the lost item.
                            </p>

                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                                required>

                        </div>

                    </div>

                </div>


                <!-- INFO -->

                <div class="lost-info">

                    <strong>💡 Tip:</strong>

                    Give accurate information about the item and
                    location so other students can identify it easily.

                </div>


                <!-- BUTTONS -->

                <div class="lost-buttons">

                    <a
                        href="../student_dashboard.php"
                        class="lost-cancel">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        name="submit"
                        class="lost-submit">

                        🔎 Submit Lost Item

                    </button>

                </div>

            </form>

        </div>


        <!-- PROCESS -->

        <div class="lost-process">

            <h3>How Lost & Found Works</h3>

            <div class="lost-steps">

                <div class="lost-step">

                    <div class="step-number">1</div>

                    <h4>Report</h4>

                    <p>Submit details of your lost item.</p>

                </div>


                <div class="lost-step">

                    <div class="step-number">2</div>

                    <h4>Search</h4>

                    <p>Students can check reported items.</p>

                </div>


                <div class="lost-step">

                    <div class="step-number">3</div>

                    <h4>Identify</h4>

                    <p>Match the item with its owner.</p>

                </div>


                <div class="lost-step">

                    <div class="step-number">4</div>

                    <h4>Return</h4>

                    <p>The item can be safely returned.</p>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>