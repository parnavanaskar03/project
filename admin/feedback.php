<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="admin"){
    header("Location: ../login.php");
    exit();
}

$query = mysqli_query($conn,"

SELECT feedback.*, users.fullname

FROM feedback

INNER JOIN users

ON feedback.user_id = users.id

ORDER BY feedback.id DESC

");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Feedback Management</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial,sans-serif;
    background:#f4f7fb;
    color:#334155;
}

.feedback-admin-page{
    min-height:100vh;
    padding:35px 20px;
}

.feedback-admin-container{
    max-width:1050px;
    margin:auto;
}


/* HEADER */

.feedback-admin-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.feedback-admin-header h1{
    color:#172554;
    font-size:28px;
}

.feedback-admin-header p{
    color:#64748b;
    font-size:14px;
    margin-top:7px;
}

.back-btn{
    text-decoration:none;
    background:#172554;
    color:white;
    padding:12px 18px;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
}

.back-btn:hover{
    background:#2563eb;
}


/* INFO */

.feedback-info{
    display:flex;
    align-items:flex-start;
    gap:14px;
    background:#eff6ff;
    border-left:5px solid #2563eb;
    padding:17px 20px;
    border-radius:10px;
    margin-bottom:22px;
}

.feedback-info-icon{
    font-size:22px;
}

.feedback-info strong{
    color:#172554;
    font-size:14px;
}

.feedback-info p{
    color:#475569;
    font-size:12px;
    line-height:1.5;
    margin-top:5px;
}


/* LIST */

.feedback-list{
    display:flex;
    flex-direction:column;
    gap:18px;
}


/* CARD */

.feedback-card{
    background:white;
    border-radius:16px;
    padding:23px 25px;
    box-shadow:0 5px 18px rgba(0,0,0,.07);
    border-left:5px solid #f59e0b;
}


/* TOP */

.feedback-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:15px;
    padding-bottom:16px;
    border-bottom:1px solid #eef2f7;
}

.feedback-student{
    display:flex;
    align-items:center;
    gap:12px;
}

.student-icon{
    width:43px;
    height:43px;
    border-radius:50%;
    background:#dbeafe;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:20px;
}

.feedback-student small{
    display:block;
    color:#94a3b8;
    font-size:10px;
    margin-bottom:4px;
}

.feedback-student strong{
    color:#172554;
    font-size:14px;
}

.feedback-id{
    color:#94a3b8;
    font-size:11px;
}


/* RATING */

.rating-box{
    margin-top:18px;
    display:flex;
    align-items:center;
    gap:12px;
}

.rating-box strong{
    color:#334155;
    font-size:13px;
}

.stars{
    letter-spacing:2px;
    font-size:19px;
}

.rating-number{
    background:#fef3c7;
    color:#92400e;
    padding:5px 9px;
    border-radius:15px;
    font-size:11px;
    font-weight:bold;
}


/* MESSAGE */

.feedback-message{
    margin-top:17px;
    background:#f8fafc;
    border-radius:10px;
    padding:16px;
}

.feedback-message strong{
    color:#334155;
    font-size:13px;
}

.feedback-message p{
    color:#64748b;
    font-size:13px;
    line-height:1.6;
    margin-top:7px;
}


/* DATE */

.feedback-date{
    margin-top:15px;
    padding-top:13px;
    border-top:1px solid #eef2f7;
    color:#94a3b8;
    font-size:11px;
}


/* EMPTY */

.empty-box{
    background:white;
    border-radius:16px;
    padding:60px 20px;
    text-align:center;
    box-shadow:0 5px 18px rgba(0,0,0,.06);
}

.empty-icon{
    width:70px;
    height:70px;
    margin:auto;
    border-radius:50%;
    background:#fef3c7;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
}

.empty-box h2{
    color:#172554;
    font-size:21px;
    margin-top:18px;
}

.empty-box p{
    color:#64748b;
    font-size:13px;
    margin-top:7px;
}


/* MOBILE */

@media(max-width:650px){

    .feedback-admin-page{
        padding:20px 12px;
    }

    .feedback-admin-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .feedback-admin-header h1{
        font-size:24px;
    }

    .back-btn{
        width:100%;
        text-align:center;
    }

    .feedback-card{
        padding:20px;
    }

    .feedback-top{
        flex-direction:column;
    }

    .rating-box{
        flex-wrap:wrap;
    }

}

</style>

</head>

<body>

<div class="feedback-admin-page">

<div class="feedback-admin-container">


    <!-- HEADER -->

    <div class="feedback-admin-header">

        <div>

            <h1>
                Feedback Management
            </h1>

            <p>
                View feedback and ratings submitted by students
            </p>

        </div>


        <a
            href="dashboard.php"
            class="back-btn"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- INFO -->

    <div class="feedback-info">

        <div class="feedback-info-icon">
            ⭐
        </div>

        <div>

            <strong>
                Student Feedback
            </strong>

            <p>
                Review student opinions, ratings and suggestions
                to understand the campus experience better.
            </p>

        </div>

    </div>


    <!-- FEEDBACK LIST -->

    <div class="feedback-list">


    <?php

    if(mysqli_num_rows($query) > 0){

        while($row=mysqli_fetch_assoc($query)){

            $rating = (int)$row['rating'];

            $stars = "";

            for($i=1; $i<=5; $i++){

                if($i <= $rating){
                    $stars .= "★";
                }
                else{
                    $stars .= "☆";
                }

            }

    ?>


        <div class="feedback-card">


            <!-- TOP -->

            <div class="feedback-top">

                <div class="feedback-student">

                    <div class="student-icon">
                        👨‍🎓
                    </div>

                    <div>

                        <small>
                            Submitted By
                        </small>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $row['fullname']
                            );
                            ?>
                        </strong>

                    </div>

                </div>


                <span class="feedback-id">

                    Feedback #<?php
                    echo htmlspecialchars(
                        $row['id']
                    );
                    ?>

                </span>

            </div>


            <!-- RATING -->

            <div class="rating-box">

                <strong>
                    Rating
                </strong>

                <span class="stars">
                    <?php echo $stars; ?>
                </span>

                <span class="rating-number">

                    <?php
                    echo $rating;
                    ?>/5

                </span>

            </div>


            <!-- MESSAGE -->

            <div class="feedback-message">

                <strong>
                    Student Feedback
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


            <!-- DATE -->

            <div class="feedback-date">

                📅 Submitted:
                <?php

                echo htmlspecialchars(
                    $row['created_at']
                );

                ?>

            </div>


        </div>


    <?php

        }

    }
    else{

    ?>


        <div class="empty-box">

            <div class="empty-icon">
                ⭐
            </div>

            <h2>
                No Feedback Found
            </h2>

            <p>
                Students have not submitted any feedback yet.
            </p>

        </div>


    <?php

    }

    ?>


    </div>


</div>

</div>

</body>

</html>