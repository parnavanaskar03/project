<?php

session_start();

include("../config/db.php");

if(!isset($_SESSION['user_id'])){

    header("Location: ../login.php");
    exit();

}

$query = mysqli_query($conn,"

SELECT * FROM notices

WHERE status='Active'

AND (target_role='All' OR target_role='student')

ORDER BY id DESC

");

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Notice Board - Campus Care</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="notice-page">

    <div class="notice-container">

        <!-- HEADER -->

        <div class="notice-header">

            <h1>📢 Notice Board</h1>

            <p>
                Latest notices and important campus updates.
            </p>

        </div>


        <!-- NOTICE LIST -->

        <div class="notice-list">

            <?php

            if(mysqli_num_rows($query) > 0){

                while($row = mysqli_fetch_assoc($query)){

            ?>

                <div class="notice-card">

                    <h3>
                        📌 <?php echo htmlspecialchars($row['title']); ?>
                    </h3>

                    <div class="notice-description">

                        <?php

                        echo nl2br(
                            htmlspecialchars($row['description'])
                        );

                        ?>

                    </div>

                    <div class="notice-meta">

                        <span>
                            👤 <strong>Posted By:</strong>
                            <?php echo htmlspecialchars($row['posted_by']); ?>
                        </span>

                        <span>
                            📅 <strong>Date:</strong>
                            <?php echo htmlspecialchars($row['created_at']); ?>
                        </span>

                    </div>

                </div>

            <?php

                }

            }else{

            ?>

                <div class="no-notice">

                    📭

                    <h3>No Notice Available</h3>

                    <p>
                        There are no active notices for students at the moment.
                    </p>

                </div>

            <?php

            }

            ?>

        </div>


        <!-- BACK -->

        <a
            href="../student_dashboard.php"
            class="notice-back"
        >
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>