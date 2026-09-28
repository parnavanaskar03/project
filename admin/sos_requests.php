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


/* =========================
   GET ALL SOS REQUESTS
========================= */

$query = mysqli_query($conn,"

SELECT sos_requests.*, users.fullname

FROM sos_requests

INNER JOIN users

ON sos_requests.user_id = users.id

ORDER BY sos_requests.id DESC

");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Campus Care - SOS Requests</title>


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


/* =========================
   PAGE
========================= */

.sos-admin-page{
    min-height:100vh;
    padding:35px 20px;
}

.sos-admin-container{
    max-width:1100px;
    margin:auto;
}


/* =========================
   HEADER
========================= */

.sos-admin-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.sos-admin-header h1{
    color:#172554;
    font-size:28px;
}

.sos-admin-header p{
    color:#64748b;
    font-size:14px;
    margin-top:7px;
}

.back-dashboard{
    text-decoration:none;
    background:#172554;
    color:white;
    padding:12px 18px;
    border-radius:9px;
    font-size:13px;
    font-weight:600;
}

.back-dashboard:hover{
    background:#2563eb;
}


/* =========================
   WARNING
========================= */

.sos-warning{
    display:flex;
    align-items:flex-start;
    gap:14px;

    background:#fff7ed;

    border-left:5px solid #f97316;

    padding:17px 20px;

    border-radius:10px;

    margin-bottom:22px;
}

.sos-warning-icon{
    width:43px;
    height:43px;

    min-width:43px;

    border-radius:50%;

    background:#ffedd5;

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:21px;
}

.sos-warning strong{
    display:block;

    color:#9a3412;

    font-size:14px;
}

.sos-warning p{
    color:#7c2d12;

    font-size:12px;

    line-height:1.5;

    margin-top:5px;
}


/* =========================
   LIST
========================= */

.sos-list{
    display:flex;
    flex-direction:column;
    gap:18px;
}


/* =========================
   SOS CARD
========================= */

.sos-card{
    background:white;

    border-radius:17px;

    padding:23px 25px;

    box-shadow:0 5px 20px rgba(0,0,0,.07);

    border-left:5px solid #dc2626;
}


/* =========================
   CARD TOP
========================= */

.sos-card-top{
    display:flex;

    justify-content:space-between;

    align-items:flex-start;

    gap:15px;

    padding-bottom:17px;

    border-bottom:1px solid #eef2f7;
}

.sos-title{
    flex:1;
}

.sos-id{
    color:#94a3b8;

    font-size:11px;
}

.sos-title h2{
    color:#172554;

    font-size:19px;

    margin-top:5px;
}


/* =========================
   STATUS
========================= */

.status-badge{
    padding:7px 13px;

    border-radius:20px;

    font-size:11px;

    font-weight:bold;

    white-space:nowrap;
}

.status-pending{
    background:#fef3c7;
    color:#92400e;
}

.status-accepted{
    background:#dbeafe;
    color:#1d4ed8;
}

.status-resolved{
    background:#dcfce7;
    color:#15803d;
}

.status-other{
    background:#e2e8f0;
    color:#475569;
}


/* =========================
   DETAILS
========================= */

.sos-details{
    display:grid;

    grid-template-columns:1fr 1fr;

    gap:15px;

    margin-top:18px;
}

.sos-detail{
    background:#f8fafc;

    border-radius:10px;

    padding:14px;

    display:flex;

    align-items:flex-start;

    gap:10px;
}

.detail-icon{
    font-size:19px;
}

.sos-detail small{
    display:block;

    color:#94a3b8;

    font-size:10px;

    margin-bottom:4px;
}

.sos-detail strong{
    display:block;

    color:#475569;

    font-size:13px;

    word-break:break-word;
}


/* =========================
   MESSAGE
========================= */

.sos-message{
    margin-top:18px;

    background:#fef2f2;

    border-radius:10px;

    padding:16px;
}

.sos-message strong{
    color:#991b1b;

    font-size:13px;
}

.sos-message p{
    color:#475569;

    font-size:13px;

    line-height:1.6;

    margin-top:7px;
}


/* =========================
   UPDATE SECTION
========================= */

.update-section{
    margin-top:20px;

    padding-top:18px;

    border-top:1px solid #eef2f7;
}

.update-section h3{
    color:#172554;

    font-size:14px;

    margin-bottom:15px;
}

.update-form{
    display:grid;

    grid-template-columns:180px 1fr auto;

    gap:12px;

    align-items:end;
}

.form-group{
    display:flex;

    flex-direction:column;
}

.form-group label{
    color:#334155;

    font-size:11px;

    font-weight:600;

    margin-bottom:7px;
}

.form-group select,
.form-group input{
    width:100%;

    height:43px;

    border:1px solid #d7dee8;

    border-radius:8px;

    padding:0 12px;

    background:white;

    color:#334155;

    font-size:13px;

    outline:none;
}

.form-group select:focus,
.form-group input:focus{
    border-color:#2563eb;

    box-shadow:0 0 0 3px rgba(37,99,235,.10);
}

.update-btn{
    height:43px;

    border:none;

    border-radius:8px;

    background:#dc2626;

    color:white;

    padding:0 22px;

    font-size:13px;

    font-weight:600;

    cursor:pointer;
}

.update-btn:hover{
    background:#b91c1c;
}


/* =========================
   DATE
========================= */

.sos-date{
    margin-top:15px;

    color:#94a3b8;

    font-size:11px;
}


/* =========================
   EMPTY
========================= */

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

    background:#fee2e2;

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


/* =========================
   MOBILE
========================= */

@media(max-width:750px){

    .sos-admin-page{
        padding:20px 12px;
    }

    .sos-admin-header{
        flex-direction:column;

        align-items:flex-start;

        gap:15px;
    }

    .sos-admin-header h1{
        font-size:24px;
    }

    .back-dashboard{
        width:100%;

        text-align:center;
    }

    .sos-card{
        padding:20px;
    }

    .sos-card-top{
        flex-direction:column;
    }

    .sos-details{
        grid-template-columns:1fr;
    }

    .update-form{
        grid-template-columns:1fr;
    }

    .update-btn{
        width:100%;
    }

}

</style>

</head>


<body>


<div class="sos-admin-page">

<div class="sos-admin-container">


    <!-- HEADER -->

    <div class="sos-admin-header">

        <div>

            <h1>
                SOS Request Management
            </h1>

            <p>
                Monitor and manage emergency requests from students
            </p>

        </div>


        <a
            href="dashboard.php"
            class="back-dashboard"
        >
            ← Back to Dashboard
        </a>

    </div>


    <!-- WARNING -->

    <div class="sos-warning">

        <div class="sos-warning-icon">
            🚨
        </div>

        <div>

            <strong>
                Emergency Request Monitoring
            </strong>

            <p>
                Please review SOS requests carefully and update
                their status after taking the necessary action.
            </p>

        </div>

    </div>


    <!-- SOS LIST -->

    <div class="sos-list">


    <?php

    if(mysqli_num_rows($query) > 0){

        while($row=mysqli_fetch_assoc($query)){

            $status = strtolower($row['status']);

            if($status == "pending"){

                $status_class = "status-pending";

            }
            elseif($status == "accepted"){

                $status_class = "status-accepted";

            }
            elseif($status == "resolved"){

                $status_class = "status-resolved";

            }
            else{

                $status_class = "status-other";

            }

    ?>


        <div class="sos-card">


            <!-- TOP -->

            <div class="sos-card-top">

                <div class="sos-title">

                    <span class="sos-id">
                        SOS Request #<?php
                        echo htmlspecialchars($row['id']);
                        ?>
                    </span>

                    <h2>
                        🚨 Emergency Request
                    </h2>

                </div>


                <span class="status-badge <?php
                echo $status_class;
                ?>">

                    <?php
                    echo htmlspecialchars($row['status']);
                    ?>

                </span>

            </div>


            <!-- DETAILS -->

            <div class="sos-details">


                <!-- STUDENT -->

                <div class="sos-detail">

                    <span class="detail-icon">
                        👨‍🎓
                    </span>

                    <div>

                        <small>
                            Student
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


                <!-- LOCATION -->

                <div class="sos-detail">

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


            <!-- UPDATE -->

            <div class="update-section">

                <h3>
                    Update SOS Request
                </h3>


                <form
                    action="update_sos.php"
                    method="POST"
                    class="update-form"
                >


                    <input
                        type="hidden"
                        name="id"
                        value="<?php
                        echo htmlspecialchars(
                            $row['id']
                        );
                        ?>"
                    >


                    <!-- STATUS -->

                    <div class="form-group">

                        <label>
                            Status
                        </label>

                        <select name="status">

                            <option
                                value="Pending"
                                <?php
                                if($row['status']=="Pending")
                                    echo "selected";
                                ?>
                            >
                                Pending
                            </option>

                            <option
                                value="Accepted"
                                <?php
                                if($row['status']=="Accepted")
                                    echo "selected";
                                ?>
                            >
                                Accepted
                            </option>

                            <option
                                value="Resolved"
                                <?php
                                if($row['status']=="Resolved")
                                    echo "selected";
                                ?>
                            >
                                Resolved
                            </option>

                        </select>

                    </div>


                    <!-- REMARKS -->

                    <div class="form-group">

                        <label>
                            Admin Remarks
                        </label>

                        <input
                            type="text"
                            name="remarks"
                            value="<?php
                            echo htmlspecialchars(
                                $row['remarks'] ?? ''
                            );
                            ?>"
                            placeholder="Enter remarks"
                        >

                    </div>


                    <!-- UPDATE -->

                    <button
                        type="submit"
                        name="update"
                        class="update-btn"
                    >
                        Update SOS
                    </button>


                </form>

            </div>


            <!-- DATE -->

            <div class="sos-date">

                Submitted:
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


        <!-- EMPTY -->

        <div class="empty-box">

            <div class="empty-icon">
                🚨
            </div>

            <h2>
                No SOS Requests
            </h2>

            <p>
                There are currently no emergency SOS requests.
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