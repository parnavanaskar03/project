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

SELECT lost_found.*, users.fullname

FROM lost_found

INNER JOIN users

ON lost_found.user_id = users.id

ORDER BY lost_found.id DESC

");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - Lost & Found</title>

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

.lost-admin-page{
    min-height:100vh;
    padding:35px 20px;
}

.lost-admin-container{
    max-width:1100px;
    margin:auto;
}

/* HEADER */

.lost-admin-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.lost-admin-header h1{
    color:#172554;
    font-size:28px;
}

.lost-admin-header p{
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

.info-box{
    background:#eff6ff;
    border-left:5px solid #2563eb;
    padding:16px 18px;
    border-radius:10px;
    margin-bottom:22px;
}

.info-box strong{
    color:#172554;
    font-size:14px;
}

.info-box p{
    color:#475569;
    font-size:12px;
    line-height:1.5;
    margin-top:5px;
}

/* LIST */

.lost-list{
    display:flex;
    flex-direction:column;
    gap:18px;
}

/* CARD */

.lost-card{
    background:white;
    border-radius:17px;
    padding:22px;
    box-shadow:0 5px 20px rgba(0,0,0,.07);
    display:flex;
    gap:22px;
    border-left:5px solid #2563eb;
}

.lost-image{
    width:180px;
    min-width:180px;
    height:155px;
    border-radius:12px;
    overflow:hidden;
    background:#f1f5f9;
}

.lost-image img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.no-image{
    width:100%;
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:45px;
    color:#94a3b8;
}

/* DETAILS */

.lost-details{
    flex:1;
}

.lost-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:15px;
}

.lost-top h2{
    color:#172554;
    font-size:20px;
}

.item-id{
    display:block;
    color:#94a3b8;
    font-size:11px;
    margin-top:5px;
}

/* STATUS */

.status{
    padding:7px 13px;
    border-radius:20px;
    font-size:11px;
    font-weight:bold;
    white-space:nowrap;
}

.status-lost{
    background:#fee2e2;
    color:#b91c1c;
}

.status-found{
    background:#dcfce7;
    color:#15803d;
}

.status-other{
    background:#e2e8f0;
    color:#475569;
}

/* STUDENT */

.student-box{
    margin-top:15px;
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}

.student-box div{
    background:#f8fafc;
    padding:10px 13px;
    border-radius:8px;
}

.student-box small{
    display:block;
    color:#94a3b8;
    font-size:10px;
    margin-bottom:4px;
}

.student-box strong{
    color:#475569;
    font-size:12px;
}

/* DESCRIPTION */

.item-description{
    margin-top:16px;
}

.item-description strong{
    color:#334155;
    font-size:13px;
}

.item-description p{
    color:#64748b;
    font-size:13px;
    line-height:1.5;
    margin-top:6px;
}

/* META */

.item-meta{
    display:flex;
    gap:25px;
    flex-wrap:wrap;
    margin-top:17px;
    padding-top:14px;
    border-top:1px solid #eef2f7;
}

.item-meta div{
    display:flex;
    align-items:center;
    gap:7px;
}

.item-meta span{
    font-size:17px;
}

.item-meta small{
    display:block;
    color:#94a3b8;
    font-size:10px;
}

.item-meta strong{
    display:block;
    color:#475569;
    font-size:12px;
    margin-top:2px;
}

/* ACTION */

.action-row{
    display:flex;
    justify-content:flex-end;
    margin-top:17px;
}

.update-btn{
    text-decoration:none;
    background:#2563eb;
    color:white;
    padding:10px 20px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
}

.update-btn:hover{
    background:#1d4ed8;
}

/* EMPTY */

.empty-box{
    background:white;
    border-radius:17px;
    padding:60px 20px;
    text-align:center;
    box-shadow:0 5px 18px rgba(0,0,0,.06);
}

.empty-icon{
    width:70px;
    height:70px;
    margin:auto;
    border-radius:50%;
    background:#dbeafe;
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

@media(max-width:700px){

    .lost-admin-page{
        padding:20px 12px;
    }

    .lost-admin-header{
        flex-direction:column;
        align-items:flex-start;
        gap:15px;
    }

    .lost-admin-header h1{
        font-size:24px;
    }

    .back-btn{
        width:100%;
        text-align:center;
    }

    .lost-card{
        flex-direction:column;
        padding:18px;
    }

    .lost-image{
        width:100%;
        height:210px;
    }

    .lost-top{
        flex-direction:column;
    }

    .student-box{
        flex-direction:column;
    }

    .student-box div{
        width:100%;
    }

    .item-meta{
        flex-direction:column;
        gap:12px;
    }

    .action-row{
        justify-content:stretch;
    }

    .update-btn{
        width:100%;
        text-align:center;
    }

}

</style>

</head>

<body>

<div class="lost-admin-page">

<div class="lost-admin-container">


    <!-- HEADER -->

    <div class="lost-admin-header">

        <div>

            <h1>
                Lost & Found Management
            </h1>

            <p>
                Manage items reported by students
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

    <div class="info-box">

        <strong>
            🔎 Lost & Found Management
        </strong>

        <p>
            Review items reported by students and update
            their status when an item is found or handled.
        </p>

    </div>


    <!-- LIST -->

    <div class="lost-list">


    <?php

    if(mysqli_num_rows($query) > 0){

        while($row=mysqli_fetch_assoc($query)){

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


        <div class="lost-card">


            <!-- IMAGE -->

            <div class="lost-image">

                <?php

                if(!empty($row['image'])){

                ?>

                    <img
                        src="../uploads/<?php
                        echo htmlspecialchars($row['image']);
                        ?>"
                        alt="Lost Item"
                    >

                <?php

                }else{

                ?>

                    <div class="no-image">
                        🔎
                    </div>

                <?php

                }

                ?>

            </div>


            <!-- DETAILS -->

            <div class="lost-details">


                <!-- TOP -->

                <div class="lost-top">

                    <div>

                        <h2>
                            <?php
                            echo htmlspecialchars(
                                $row['item_name']
                            );
                            ?>
                        </h2>

                        <span class="item-id">
                            Item #<?php
                            echo htmlspecialchars(
                                $row['id']
                            );
                            ?>
                        </span>

                    </div>


                    <span class="status <?php
                    echo $status_class;
                    ?>">

                        <?php
                        echo htmlspecialchars(
                            $row['status']
                        );
                        ?>

                    </span>

                </div>


                <!-- STUDENT -->

                <div class="student-box">

                    <div>

                        <small>
                            Reported By
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


                <!-- DESCRIPTION -->

                <div class="item-description">

                    <strong>
                        Description
                    </strong>

                    <p>
                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $row['description']
                            )
                        );

                        ?>
                    </p>

                </div>


                <!-- META -->

                <div class="item-meta">


                    <div>

                        <span>
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


                    <div>

                        <span>
                            📅
                        </span>

                        <div>

                            <small>
                                Reported On
                            </small>

                            <strong>
                                <?php
                                echo htmlspecialchars(
                                    $row['created_at']
                                );
                                ?>
                            </strong>

                        </div>

                    </div>


                </div>


                <!-- ACTION -->

                <div class="action-row">

                    <a
                        href="update_lost_found.php?id=<?php
                        echo htmlspecialchars($row['id']);
                        ?>"
                        class="update-btn"
                    >
                        ✏️ Update Status
                    </a>

                </div>


            </div>


        </div>


    <?php

        }

    }
    else{

    ?>


        <div class="empty-box">

            <div class="empty-icon">
                🔎
            </div>

            <h2>
                No Lost Items Found
            </h2>

            <p>
                There are currently no Lost & Found reports.
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