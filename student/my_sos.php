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
    FROM sos_requests
    WHERE user_id='$user_id'
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>My SOS Requests - Campus Care</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="sos-status-page">

    <div class="sos-status-container">

        <!-- HEADER -->

        <div class="sos-status-header">

            <div>

                <h1>🚨 My SOS Requests</h1>

                <p>
                    Track the status of your emergency requests.
                </p>

            </div>

            <a
                href="../student_dashboard.php"
                class="sos-dashboard-btn">

                ← Dashboard

            </a>

        </div>


        <!-- WARNING -->

        <div class="sos-warning">

            <div class="sos-warning-icon">
                🚨
            </div>

            <div>

                <strong>Emergency Request Status</strong>

                <p>
                    This page shows the current status and remarks
                    of your submitted SOS requests.
                </p>

            </div>

        </div>


        <?php if(mysqli_num_rows($query) > 0){ ?>


        <!-- SOS LIST -->

        <div class="sos-list">

            <?php while($row = mysqli_fetch_assoc($query)){ ?>

            <?php

                $status = strtolower(trim($row['status']));

                /*
                 * Status Design
                 */

                if($status == "pending"){

                    $status_class = "sos-pending";

                }
                elseif($status == "accepted"){

                    $status_class = "sos-progress";

                }
                elseif(
                    $status == "resolved" ||
                    $status == "completed"
                ){

                    $status_class = "sos-resolved";

                }
                else{

                    $status_class = "sos-other";

                }

            ?>


            <div class="sos-card">

                <!-- TOP -->

                <div class="sos-card-top">

                    <div>

                        <span class="sos-request-id">

                            SOS Request #<?php
                            echo htmlspecialchars($row['id']);
                            ?>

                        </span>

                        <h2>
                            🚨 Emergency Request
                        </h2>

                    </div>


                    <!-- STATUS -->

                    <span class="sos-status-badge <?php echo $status_class; ?>">

                        <?php

                        echo !empty($row['status'])
                            ? htmlspecialchars($row['status'])
                            : "Pending";

                        ?>

                    </span>

                </div>


                <!-- DETAILS -->

                <div class="sos-details">


                    <!-- LOCATION -->

                    <div class="sos-detail-box">

                        <span class="detail-icon">
                            📍
                        </span>

                        <div>

                            <small>
                                Location
                            </small>

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['location']
                                );

                                ?>

                            </strong>

                        </div>

                    </div>


                    <!-- DATE -->

                    <div class="sos-detail-box">

                        <span class="detail-icon">
                            📅
                        </span>

                        <div>

                            <small>
                                Submitted On
                            </small>

                            <strong>

                                <?php

                                echo !empty($row['created_at'])
                                    ? htmlspecialchars(
                                        $row['created_at']
                                    )
                                    : "—";

                                ?>

                            </strong>

                        </div>

                    </div>


                </div>


                <!-- MESSAGE -->

                <div class="sos-message">

                    <strong>
                        Emergency Message
                    </strong>

                    <p>

                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $row['message']
                            )
                        );

                        ?>

                    </p>

                </div>


                <!-- REMARKS -->

                <div class="sos-remarks">

                    <strong>
                        Admin / Staff Remarks
                    </strong>


                    <?php if(!empty($row['remarks'])){ ?>

                        <p>

                            <?php

                            echo nl2br(
                                htmlspecialchars(
                                    $row['remarks']
                                )
                            );

                            ?>

                        </p>

                    <?php }else{ ?>

                        <p class="no-remarks">

                            No remarks have been added yet.

                        </p>

                    <?php } ?>

                </div>


                <!-- STATUS LINE -->

                <div class="sos-status-line">

                    <span class="status-dot"></span>

                    <span>
                        Current Status:
                    </span>

                    <strong>

                        <?php

                        echo !empty($row['status'])
                            ? htmlspecialchars(
                                $row['status']
                            )
                            : "Pending";

                        ?>

                    </strong>

                </div>


            </div>


            <?php } ?>

        </div>


        <?php }else{ ?>


        <!-- EMPTY -->

        <div class="sos-empty">

            <div class="sos-empty-icon">
                🆘
            </div>

            <h2>
                No SOS Requests
            </h2>

            <p>
                You have not submitted any emergency SOS request yet.
            </p>

            <a
                href="sos.php"
                class="send-sos-btn">

                🚨 Send Emergency SOS

            </a>

        </div>


        <?php } ?>


        <!-- BOTTOM -->

        <div class="sos-bottom">

            <a href="../student_dashboard.php">

                ← Back to Dashboard

            </a>


            <a href="sos.php">

                🚨 Emergency SOS

            </a>

        </div>


    </div>

</div>

</body>

</html>