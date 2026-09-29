<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != "warden"){
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Warden Information */
$user_query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id='$user_id'
");

$user = mysqli_fetch_assoc($user_query);


/* Assigned Complaints */
$query = mysqli_query($conn, "
    SELECT complaints.*, users.fullname
    FROM complaints
    INNER JOIN users
    ON complaints.user_id = users.id
    WHERE complaints.assigned_to='warden'
    ORDER BY complaints.id DESC
");


/* Statistics */
$totalComplaints = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='warden'
"));

$pendingComplaints = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='warden'
    AND status='Pending'
"));

$progressComplaints = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='warden'
    AND status='In Progress'
"));

$resolvedComplaints = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM complaints
    WHERE assigned_to='warden'
    AND status='Resolved'
"));

?>

<!DOCTYPE html>
<html>

<head>

<title>Campus Care - Warden Dashboard</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

body{
    margin:0;
    font-family:Arial, sans-serif;
    background:#f4f6f9;
}

/* Sidebar */

.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:#12355b;
    color:white;
    padding:20px;
    box-sizing:border-box;
}

.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px;
    margin:8px 0;
    border-radius:6px;
}

.sidebar a:hover{
    background:#1d4f80;
}

.sidebar .active{
    background:#1d4f80;
}

/* Main */

.main{
    margin-left:240px;
    padding:30px;
}

/* Topbar */

.topbar{
    background:white;
    padding:18px 25px;
    border-radius:10px;
    margin-bottom:25px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
}

.topbar h1{
    margin:0;
    color:#12355b;
}

.topbar p{
    margin:5px 0 0;
    color:#666;
}

/* Welcome */

.welcome{
    background:linear-gradient(135deg,#12355b,#2878b5);
    color:white;
    padding:25px;
    border-radius:12px;
    margin-bottom:25px;
}

.welcome h2{
    margin:0 0 8px;
}

/* Statistics */

.stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:25px;
}

.stat-card{
    background:white;
    padding:20px;
    border-radius:10px;
    text-align:center;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
}

.stat-card h3{
    margin:0;
    font-size:30px;
    color:#12355b;
}

.stat-card p{
    margin:8px 0 0;
    color:#666;
}

/* Information */

.info-card{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:25px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
}

.info-card h3{
    color:#12355b;
}

/* Complaints */

.complaint-card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
    overflow-x:auto;
}

.complaint-card h2{
    color:#12355b;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:15px;
}

th{
    background:#12355b;
    color:white;
    padding:12px;
}

td{
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

select,
input[type="text"]{
    padding:8px;
    border:1px solid #ccc;
    border-radius:5px;
}

.update-btn{
    background:#12355b;
    color:white;
    border:none;
    padding:8px 14px;
    border-radius:5px;
    cursor:pointer;
}

.update-btn:hover{
    background:#1d4f80;
}

.no-data{
    text-align:center;
    padding:30px;
    color:#777;
}

/* Responsive */

@media(max-width:900px){

    .sidebar{
        width:200px;
    }

    .main{
        margin-left:200px;
    }

    .stats{
        grid-template-columns:repeat(2,1fr);
    }

}

</style>

</head>

<body>


<!-- Sidebar -->

<div class="sidebar">

<h2>Campus Care</h2>

<p style="text-align:center;">
    🛡️ Warden Panel
</p>

<hr>

<a href="dashboard.php" class="active">
    🏠 Dashboard
</a>

<a href="../department/sos_requests.php">
    🚨 SOS Requests
</a>

<a href="../department/notices.php">
    📢 Notice Board
</a>

<a href="../department/lost_found.php">
    📦 Lost & Found
</a>

<a href="../department/feedback.php">
    💬 Student Feedback
</a>

<a href="profile.php">
    👤 My Profile
</a>

<a href="../logout.php">
    🚪 Logout
</a>

</div>


<!-- Main Content -->

<div class="main">


<!-- Topbar -->

<div class="topbar">

<h1>Warden Dashboard</h1>

<p>Campus Care Management System</p>

</div>


<!-- Welcome -->

<div class="welcome">

<h2>
Welcome, <?php echo htmlspecialchars($user['fullname']); ?> 👋
</h2>

<p>
Manage and monitor complaints assigned to the Warden.
</p>

</div>


<!-- Statistics -->

<div class="stats">

<div class="stat-card">

<h3>
<?php echo $totalComplaints['total']; ?>
</h3>

<p>Total Complaints</p>

</div>


<div class="stat-card">

<h3>
<?php echo $pendingComplaints['total']; ?>
</h3>

<p>Pending</p>

</div>


<div class="stat-card">

<h3>
<?php echo $progressComplaints['total']; ?>
</h3>

<p>In Progress</p>

</div>


<div class="stat-card">

<h3>
<?php echo $resolvedComplaints['total']; ?>
</h3>

<p>Resolved</p>

</div>

</div>


<!-- Warden Information -->

<div class="info-card">

<h3>Warden Information</h3>

<p>
<b>Name:</b>
<?php echo htmlspecialchars($user['fullname']); ?>
</p>

<p>
<b>Email:</b>
<?php echo htmlspecialchars($user['email']); ?>
</p>

<p>
<b>Phone:</b>
<?php echo htmlspecialchars($user['phone']); ?>
</p>

<p>
<b>Department:</b>
<?php echo htmlspecialchars($user['department']); ?>
</p>

</div>


<!-- Complaints -->

<div class="complaint-card">

<h2>Assigned Complaints</h2>

<?php if(mysqli_num_rows($query) > 0){ ?>

<table>

<tr>

<th>ID</th>

<th>Student</th>

<th>Title</th>

<th>Category</th>

<th>Status</th>

<th>Update Status</th>

<th>Date</th>

</tr>


<?php while($row = mysqli_fetch_assoc($query)){ ?>

<tr>

<td>
<?php echo $row['id']; ?>
</td>


<td>
<?php echo htmlspecialchars($row['fullname']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['title']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['category']); ?>
</td>


<td>
<?php echo htmlspecialchars($row['status']); ?>
</td>


<td>

<form action="update_status.php" method="POST">

<input
type="hidden"
name="id"
value="<?php echo $row['id']; ?>"
>


<select name="status" required>

<option value="Pending"
<?php
if($row['status']=="Pending") echo "selected";
?>
>
Pending
</option>


<option value="In Progress"
<?php
if($row['status']=="In Progress") echo "selected";
?>
>
In Progress
</option>


<option value="Resolved"
<?php
if($row['status']=="Resolved") echo "selected";
?>
>
Resolved
</option>

</select>

<br><br>

<input
type="text"
name="remarks"
placeholder="Enter Remarks"
value="<?php echo htmlspecialchars($row['remarks'] ?? ''); ?>"
required
>

<br><br>

<button
type="submit"
name="update"
class="update-btn"
>
Update
</button>

</form>

</td>


<td>
<?php echo $row['created_at']; ?>
</td>

</tr>

<?php } ?>

</table>

<?php }else{ ?>

<div class="no-data">

No complaints assigned to the Warden yet.

</div>

<?php } ?>

</div>


</div>

</body>

</html>