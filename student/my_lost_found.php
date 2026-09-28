<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT *
    FROM lost_found
    WHERE user_id='$user_id'
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>My Lost & Found - Campus Care</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="my-lost-page">

    <div class="my-lost-container">

        <!-- HEADER -->

        <div class="my-lost-header">

            <div>

                <h1>🔎 My Lost & Found</h1>

                <p>
                    View all the items you have reported.
                </p>

            </div>

            <a
                href="../student_dashboard.php"
                class="my-lost-back">

                ← Dashboard

            </a>

        </div>


        <!-- ITEMS -->

        <?php if(mysqli_num_rows($query) > 0){ ?>

        <div class="my-lost-list">

            <?php while($row = mysqli_fetch_assoc($query)){ ?>

            <div class="my-lost-card">

                <!-- IMAGE -->

                <div class="my-lost-image">

                    <?php if(!empty($row['image'])){ ?>

                        <img
                            src="../uploads/<?php echo htmlspecialchars($row['image']); ?>"
                            alt="Lost Item">

                    <?php }else{ ?>

                        <div class="no-image">
                            📦
                        </div>

                    <?php } ?>

                </div>


                <!-- DETAILS -->

                <div class="my-lost-details">

                    <div class="item-top">

                        <div>

                            <h2>
                                <?php echo htmlspecialchars($row['item_name']); ?>
                            </h2>

                            <span class="item-id">
                                Item ID: #<?php echo $row['id']; ?>
                            </span>

                        </div>


                        <?php

                        $status = strtolower($row['status']);

                        if($status == "lost"){
                            $status_class = "status-lost";
                        }
                        elseif($status == "found"){
                            $status_class = "status-found";
                        }
                        else{
                            $status_class = "status-other";
                        }

                        ?>

                        <span class="lost-status <?php echo $status_class; ?>">

                            <?php echo htmlspecialchars($row['status']); ?>

                        </span>

                    </div>


                    <!-- DESCRIPTION -->

                    <div class="item-description">

                        <strong>Description</strong>

                        <p>
                            <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                        </p>

                    </div>


                    <!-- META -->

                    <div class="item-meta">

                        <div>

                            <span>📍</span>

                            <div>
                                <small>Last Seen</small>
                                <strong>
                                    <?php echo htmlspecialchars($row['location']); ?>
                                </strong>
                            </div>

                        </div>


                        <div>

                            <span>📅</span>

                            <div>
                                <small>Reported On</small>
                                <strong>
                                    <?php echo htmlspecialchars($row['created_at']); ?>
                                </strong>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <?php } ?>

        </div>

        <?php }else{ ?>

        <!-- EMPTY STATE -->

        <div class="my-lost-empty">

            <div class="empty-icon">
                🔎
            </div>

            <h2>No Lost Items Reported</h2>

            <p>
                You have not reported any lost item yet.
            </p>

            <a
                href="lost_found.php"
                class="report-lost-btn">

                + Report Lost Item

            </a>

        </div>

        <?php } ?>


        <!-- BOTTOM BUTTON -->

        <div class="my-lost-bottom">

            <a href="../student_dashboard.php">

                ← Back to Dashboard

            </a>

            <a href="lost_found.php">

                + Report New Item

            </a>

        </div>

    </div>

</div>

</body>

</html>