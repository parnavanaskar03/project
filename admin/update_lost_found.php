<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
    header("Location: lost_found.php");
    exit();
}

$id = intval($_GET['id']);

$query = mysqli_query($conn,
"SELECT * FROM lost_found WHERE id='$id'");

$row = mysqli_fetch_assoc($query);

if(!$row){
    header("Location: lost_found.php");
    exit();
}

if(isset($_POST['update'])){

    $status = $_POST['status'];

    $sql = "UPDATE lost_found
            SET status='$status'
            WHERE id='$id'";

    if(mysqli_query($conn,$sql)){

        echo "<script>
        alert('Status Updated Successfully');
        window.location='lost_found.php';
        </script>";

        exit();

    }else{

        echo "<script>
        alert('Update Failed');
        </script>";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Update Lost & Found - Campus Care</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{
    background:#f4f7fb;
    min-height:100vh;
}

/* TOP BAR */

.topbar{
    background:#172554;
    color:white;
    padding:20px 35px;
}

.topbar h2{
    font-size:21px;
}

.topbar p{
    color:#bfdbfe;
    font-size:13px;
    margin-top:4px;
}

/* CONTAINER */

.container{
    max-width:650px;
    margin:40px auto;
    padding:0 20px;
}

/* HEADER */

.page-header{
    margin-bottom:22px;
}

.page-header h1{
    color:#172554;
    font-size:28px;
}

.page-header p{
    color:#64748b;
    font-size:14px;
    margin-top:7px;
}

/* CARD */

.card{
    background:white;
    border-radius:18px;
    padding:30px;
    box-shadow:0 8px 25px rgba(0,0,0,0.07);
}

/* ITEM */

.item-box{
    display:flex;
    align-items:center;
    gap:15px;
    background:#f8fafc;
    padding:18px;
    border-radius:12px;
    margin-bottom:25px;
}

.item-icon{
    width:52px;
    height:52px;
    background:#dbeafe;
    color:#2563eb;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:25px;
}

.item-info h3{
    color:#172554;
    font-size:17px;
}

.item-info p{
    color:#64748b;
    font-size:12px;
    margin-top:5px;
}

/* FORM */

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    color:#334155;
    font-size:13px;
    font-weight:600;
    margin-bottom:8px;
}

.form-group input,
.form-group select{
    width:100%;
    padding:13px 14px;
    border:1px solid #dbe3ef;
    border-radius:9px;
    font-size:14px;
    outline:none;
}

.form-group input{
    background:#f8fafc;
    color:#64748b;
}

.form-group select:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.10);
}

/* INFO */

.info{
    background:#eff6ff;
    border-left:4px solid #2563eb;
    padding:13px 15px;
    border-radius:7px;
    color:#475569;
    font-size:12px;
    margin-bottom:25px;
}

/* BUTTONS */

.buttons{
    display:flex;
    gap:12px;
    padding-top:20px;
    border-top:1px solid #e5e7eb;
}

.update-btn{
    flex:1;
    border:none;
    background:#2563eb;
    color:white;
    padding:13px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
}

.update-btn:hover{
    background:#1d4ed8;
}

.back-btn{
    flex:1;
    text-align:center;
    text-decoration:none;
    background:#f1f5f9;
    color:#334155;
    padding:13px;
    border-radius:9px;
    font-size:14px;
    font-weight:600;
}

.back-btn:hover{
    background:#e2e8f0;
}

/* MOBILE */

@media(max-width:600px){

    .topbar{
        padding:18px 20px;
    }

    .container{
        margin:25px auto;
    }

    .card{
        padding:22px;
    }

    .buttons{
        flex-direction:column;
    }

}

</style>

</head>

<body>


<!-- TOP BAR -->

<div class="topbar">

    <h2>Campus Care</h2>

    <p>Admin Panel • Lost & Found Management</p>

</div>


<!-- PAGE -->

<div class="container">


    <div class="page-header">

        <h1>Update Lost & Found</h1>

        <p>Change the current status of this reported item.</p>

    </div>


    <div class="card">


        <!-- ITEM INFO -->

        <div class="item-box">

            <div class="item-icon">
                🔎
            </div>

            <div class="item-info">

                <h3>
                    <?php echo htmlspecialchars($row['item_name']); ?>
                </h3>

                <p>
                    Item ID: #<?php echo $row['id']; ?>
                </p>

            </div>

        </div>


        <!-- FORM -->

        <form method="POST">


            <div class="form-group">

                <label>Item Name</label>

                <input
                type="text"
                value="<?php echo htmlspecialchars($row['item_name']); ?>"
                readonly>

            </div>


            <div class="form-group">

                <label>Current Status</label>

                <select name="status" required>

                    <option value="Lost"
                    <?php
                    if($row['status']=="Lost")
                        echo "selected";
                    ?>>
                        Lost
                    </option>

                    <option value="Found"
                    <?php
                    if($row['status']=="Found")
                        echo "selected";
                    ?>>
                        Found
                    </option>

                    <option value="Returned"
                    <?php
                    if($row['status']=="Returned")
                        echo "selected";
                    ?>>
                        Returned
                    </option>

                </select>

            </div>


            <div class="info">

                💡 Update the status when the item is found or returned
                to the student.

            </div>


            <div class="buttons">

                <button
                type="submit"
                name="update"
                class="update-btn">

                    ✓ Update Status

                </button>


                <a
                href="lost_found.php"
                class="back-btn">

                    ← Back to Lost & Found

                </a>

            </div>


        </form>

    </div>

</div>

</body>

</html>