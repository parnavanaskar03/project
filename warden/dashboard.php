<?php

session_start();
include("../config/db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role']!="warden"){
    header("Location: ../login.php");
    exit();
}

$query = mysqli_query($conn,"
SELECT complaints.*, users.fullname
FROM complaints
INNER JOIN users
ON complaints.user_id = users.id
WHERE complaints.assigned_to='warden'
ORDER BY complaints.id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>Warden Dashboard</title>

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<header>

<h1>Campus Care</h1>

<p>Warden Dashboard</p>

</header>

<div class="register-box">

<h2>Assigned Complaints</h2>

<table border="1" width="100%" cellpadding="10">

<tr>

<th>ID</th>
<th>Student</th>
<th>Title</th>
<th>Category</th>
<th>Status</th>
<th>Remarks</th>
<th>Update</th>
<th>Date</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['fullname']; ?></td>

<td><?php echo $row['title']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo $row['status']; ?></td>

<td>

<form action="update_status.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<select name="status">

<option value="Pending">Pending</option>

<option value="In Progress">In Progress</option>

<option value="Resolved">Resolved</option>

</select>

<br><br>

<input
type="text"
name="remarks"
placeholder="Enter Remarks"
value="<?php echo $row['remarks']; ?>">

</td>

<td>

<button type="submit" name="update">

Update

</button>

</form>

</td>

<td><?php echo $row['created_at']; ?></td>

</tr>

<?php

}

?>

</table>

<br><br>

<a href="../department/sos_requests.php">

<button>

🚨 SOS Requests

</button>

</a>

<br><br>

<a href="../department/notices.php">

<button>

📢 Notice Board

</button>

</a>

<br><br>

<a href="../department/lost_found.php">

<button>

📦 Lost & Found

</button>

</a>

<br><br>

<a href="../department/feedback.php">

<button>

💬 Student Feedback

</button>

</a>

<br><br>

<a href="../logout.php">

<button>

Logout

</button>

</a>

</div>

</body>

</html>