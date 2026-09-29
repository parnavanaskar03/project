<?php

session_start();

include("../config/db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT complaints.*, 
            users.fullname AS teacher_name
     FROM complaints
     LEFT JOIN users
     ON complaints.assigned_to = users.id
     AND users.role = 'teacher'
     WHERE complaints.user_id='$user_id'
     ORDER BY complaints.id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Complaints - Campus Care</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="my-complaints-page">

    <div class="my-complaints-container">

        <!-- HEADER -->

        <div class="my-complaints-header">

            <h1>📋 My Complaints</h1>

            <p>
                Track the complaints you have submitted.
            </p>

        </div>


        <!-- COMPLAINT TABLE -->

        <div class="complaints-card">

            <table class="complaints-table">

                <tr>

                    <th>ID</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Description</th>

                    <th>Assigned To</th>

                    <th>Status</th>

                    <th>Remarks</th>

                    <th>Date</th>

                </tr>


                <?php

                if (mysqli_num_rows($query) > 0) {

                    while ($row = mysqli_fetch_assoc($query)) {

                        $status = strtolower(trim($row['status']));

                        if ($status == "pending") {

                            $status_class = "status-pending";

                        } elseif (
                            $status == "in progress" ||
                            $status == "in_progress"
                        ) {

                            $status_class = "status-progress";

                        } elseif ($status == "resolved") {

                            $status_class = "status-resolved";

                        } else {

                            $status_class = "status-default";

                        }

                ?>

                <tr>

                    <td class="complaint-id">
                        #<?php echo htmlspecialchars($row['id']); ?>
                    </td>


                    <td class="complaint-title">

                        <?php
                        echo htmlspecialchars($row['title']);
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars($row['category']);
                        ?>

                    </td>


                    <td class="complaint-description">

                        <?php
                        echo nl2br(
                            htmlspecialchars($row['description'])
                        );
                        ?>

                    </td>


                    <td>

                        <?php

                        if (!empty($row['teacher_name'])) {

                            echo htmlspecialchars($row['teacher_name']);

                        } else {

                            echo "Not Assigned";

                        }

                        ?>

                    </td>


                    <td>

                        <span class="status <?php echo $status_class; ?>">

                            <?php

                            echo !empty($row['status'])
                                ? htmlspecialchars($row['status'])
                                : "Pending";

                            ?>

                        </span>

                    </td>


                    <td class="complaint-remarks">

                        <?php

                        echo !empty($row['remarks'])
                            ? htmlspecialchars($row['remarks'])
                            : "No remarks yet";

                        ?>

                    </td>


                    <td>

                        <?php

                        echo !empty($row['created_at'])
                            ? htmlspecialchars($row['created_at'])
                            : "—";

                        ?>

                    </td>

                </tr>

                <?php

                    }

                } else {

                ?>

                <tr>

                    <td colspan="8" class="no-complaints">

                        📭 No Complaints Found

                        <br><br>

                        You have not submitted any complaints yet.

                    </td>

                </tr>

                <?php

                }

                ?>

            </table>

        </div>


        <!-- BACK BUTTON -->

        <a
            href="../student_dashboard.php"
            class="dashboard-back"
        >
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>