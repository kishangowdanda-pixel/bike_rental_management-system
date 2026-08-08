<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['login'])==0)
{
    header('location:index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Bike Rental | Payment History</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<style>

body{
    background:#f4f7fb;
    font-family:'Segoe UI',sans-serif;
}

/* HEADER */
.page-header{
    background:linear-gradient(135deg,#007bff,#003b8e);
    padding:60px 0;
    color:#fff;
    text-align:center;
}

.page-header h1{
    font-size:40px;
    font-weight:700;
    margin-bottom:10px;
}

.page-header p{
    font-size:18px;
    opacity:0.9;
}

/* CARD */
.history-card{
    background:#fff;
    border-radius:20px;
    padding:35px;
    margin-top:40px;
    margin-bottom:50px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* TABLE */
.table{
    margin-top:20px;
}

.table thead{
    background:#007bff;
    color:#fff;
}

.table th{
    border:none !important;
    padding:15px;
    font-size:15px;
}

.table td{
    vertical-align:middle !important;
    padding:15px;
}

/* STATUS */
.status-paid{
    background:#28a745;
    color:#fff;
    padding:8px 15px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.status-pending{
    background:#ffc107;
    color:#fff;
    padding:8px 15px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.status-cancel{
    background:#dc3545;
    color:#fff;
    padding:8px 15px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

/* EMPTY */
.empty-box{
    text-align:center;
    padding:40px;
    color:#777;
}

/* ANIMATION */
.history-card{
    animation:fadeIn 0.5s ease;
}

@keyframes fadeIn{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* MOBILE */
@media(max-width:768px){

.page-header h1{
    font-size:28px;
}

.history-card{
    padding:20px;
}

.table th,
.table td{
    font-size:13px;
}

}

</style>

</head>

<body>

<?php include('includes/header.php');?>

<!-- PAGE HEADER -->
<section class="page-header">

<div class="container">

<h1>
<i class="fa fa-credit-card"></i>
Payment History
</h1>

<p>
View all your bike booking payments
</p>

</div>

</section>

<!-- PAYMENT HISTORY -->
<div class="container">

<div class="history-card">

<h3>
<i class="fa fa-list"></i>
Booking Payments
</h3>

<div class="table-responsive">

<table class="table table-hover table-bordered">

<thead>

<tr>

<th>#</th>
<th>Booking ID</th>
<th>Bike Name</th>
<th>From Date</th>
<th>To Date</th>
<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$email = $_SESSION['login'];

$sql = "SELECT tblbooking.*, tblvehicles.VehiclesTitle
FROM tblbooking
JOIN tblvehicles 
ON tblvehicles.id = tblbooking.VehicleId
WHERE tblbooking.userEmail = :email
ORDER BY tblbooking.id DESC";

$query = $dbh->prepare($sql);

$query->bindParam(':email',$email,PDO::PARAM_STR);

$query->execute();

$results = $query->fetchAll(PDO::FETCH_OBJ);

$cnt = 1;

if($query->rowCount() > 0)
{

foreach($results as $result)
{

?>

<tr>

<td>
<?php echo htmlentities($cnt); ?>
</td>

<td>
BK-<?php echo htmlentities($result->id); ?>
</td>

<td>
<?php echo htmlentities($result->VehiclesTitle); ?>
</td>

<td>
<?php echo htmlentities($result->FromDate); ?>
</td>

<td>
<?php echo htmlentities($result->ToDate); ?>
</td>

<td>

<?php
if($result->Status == 1)
{
?>

<span class="status-paid">
Paid
</span>

<?php
}
else if($result->Status == 2)
{
?>

<span class="status-cancel">
Cancelled
</span>

<?php
}
else
{
?>

<span class="status-pending">
Pending
</span>

<?php } ?>

</td>

</tr>

<?php

$cnt++;

}

}
else
{

?>

<tr>

<td colspan="6">

<div class="empty-box">

<i class="fa fa-credit-card" style="font-size:50px;"></i>

<h4>No Payment History Found</h4>

<p>
You have not made any bookings yet.
</p>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include('includes/footer.php');?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>