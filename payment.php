<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(strlen($_SESSION['login'])==0)
{
    header('location:index.php');
    exit();
}
else
{

$bid = intval($_GET['bid']);

$sql = "SELECT tblbooking.*,
tblvehicles.VehiclesTitle,
tblvehicles.PricePerDay,
tblvehicles.Vimage1

FROM tblbooking

JOIN tblvehicles 
ON tblvehicles.id = tblbooking.VehicleId

WHERE tblbooking.id=:bid";

$query = $dbh->prepare($sql);

$query->bindParam(':bid',$bid,PDO::PARAM_STR);

$query->execute();

$result = $query->fetch(PDO::FETCH_OBJ);

if(!$result)
{
    echo "<script>alert('Invalid Booking ID');</script>";
    echo "<script>window.location.href='index.php';</script>";
}

if(isset($_POST['paynow']))
{

$paymentmethod = $_POST['paymentmethod'];

$transactionid = strtoupper("TXN".rand(100000,999999));

$fromdate = strtotime($result->FromDate);
$todate = strtotime($result->ToDate);

$days = ($todate - $fromdate) / (60 * 60 * 24);

if($days <= 0)
{
    $days = 1;
}

$amount = $days * $result->PricePerDay;

$status = "Paid";

$sql1 = "INSERT INTO tblpayments
(
BookingId,
UserEmail,
Amount,
PaymentMethod,
TransactionId,
PaymentStatus
)

VALUES
(
:bid,
:email,
:amount,
:paymentmethod,
:transactionid,
:status
)";

$query1 = $dbh->prepare($sql1);

$query1->bindParam(':bid',$bid,PDO::PARAM_STR);

$query1->bindParam(':email',$_SESSION['login'],PDO::PARAM_STR);

$query1->bindParam(':amount',$amount,PDO::PARAM_STR);

$query1->bindParam(':paymentmethod',$paymentmethod,PDO::PARAM_STR);

$query1->bindParam(':transactionid',$transactionid,PDO::PARAM_STR);

$query1->bindParam(':status',$status,PDO::PARAM_STR);

$query1->execute();

/* UPDATE BOOKING STATUS */

$sql2 = "UPDATE tblbooking 
SET Status='1' 
WHERE id=:bid";

$query2 = $dbh->prepare($sql2);

$query2->bindParam(':bid',$bid,PDO::PARAM_STR);

$query2->execute();

echo "<script>alert('Payment Successful');</script>";

echo "<script>
window.location.href='payment-history.php';
</script>";

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>
Bike Rental | Secure Payment
</title>

<link rel="stylesheet"
href="assets/css/bootstrap.min.css">

<link rel="stylesheet"
href="assets/css/font-awesome.min.css">

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700"
rel="stylesheet">

<style>

body{
    background:#f4f7fb;
    font-family:'Poppins',sans-serif;
}

/* HEADER */

.payment-header{
    background:linear-gradient(135deg,#007bff,#003b8e);
    padding:60px 0;
    color:#fff;
    text-align:center;
}

.payment-header h1{
    font-size:42px;
    font-weight:700;
}

.payment-header p{
    margin-top:10px;
    font-size:18px;
    opacity:0.9;
}

/* PAYMENT CARD */

.payment-card{
    background:#fff;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 12px 35px rgba(0,0,0,0.1);
    margin-top:-40px;
    margin-bottom:60px;
}

/* LEFT */

.payment-left{
    background:#f8fbff;
    padding:35px;
    border-right:1px solid #eee;
}

.payment-bike-img{
    width:100%;
    border-radius:18px;
    height:250px;
    object-fit:cover;
}

.bike-title{
    font-size:30px;
    font-weight:700;
    margin-top:20px;
    color:#222;
}

.price-tag{
    color:#007bff;
    font-size:28px;
    font-weight:700;
    margin-top:10px;
}

/* DETAILS */

.detail-box{
    background:#fff;
    padding:18px;
    border-radius:15px;
    margin-top:18px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.detail-box h5{
    font-size:16px;
    color:#666;
}

.detail-box p{
    font-size:18px;
    font-weight:600;
    margin:0;
}

/* RIGHT */

.payment-right{
    padding:40px;
}

.payment-right h2{
    font-size:34px;
    font-weight:700;
    margin-bottom:10px;
}

.secure-text{
    color:#777;
    margin-bottom:30px;
}

/* FORM */

.form-group{
    margin-bottom:22px;
}

.form-group label{
    font-weight:600;
    margin-bottom:8px;
}

.form-control{
    height:52px;
    border-radius:12px;
    border:1px solid #ddd;
    box-shadow:none;
}

.form-control:focus{
    border-color:#007bff;
    box-shadow:0 0 10px rgba(0,123,255,0.15);
}

/* SUMMARY */

.summary-box{
    background:#f8fbff;
    border-radius:18px;
    padding:25px;
    margin-top:25px;
}

.summary-item{
    display:flex;
    justify-content:space-between;
    margin-bottom:12px;
}

.summary-item h5{
    margin:0;
    color:#666;
}

.summary-item p{
    margin:0;
    font-weight:600;
}

.total-box{
    border-top:1px solid #ddd;
    padding-top:15px;
    margin-top:15px;
}

.total-box h4{
    font-weight:700;
}

.total-box p{
    font-size:26px;
    color:#007bff;
    font-weight:700;
}

/* BUTTON */

.btn-pay{
    width:100%;
    background:linear-gradient(135deg,#28a745,#1e7e34);
    border:none;
    padding:15px;
    border-radius:14px;
    color:#fff;
    font-size:18px;
    font-weight:700;
    transition:0.3s;
}

.btn-pay:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(40,167,69,0.3);
}

/* SECURITY */

.security-box{
    margin-top:20px;
    text-align:center;
    color:#666;
}

.security-box i{
    color:#28a745;
}

/* MOBILE */

@media(max-width:768px){

.payment-left{
    border-right:none;
    border-bottom:1px solid #eee;
}

.payment-header h1{
    font-size:30px;
}

.payment-right{
    padding:25px;
}

.bike-title{
    font-size:24px;
}

}

</style>

</head>

<body>

<?php include('includes/header.php');?>

<!-- HEADER -->

<section class="payment-header">

<div class="container">

<h1>
<i class="fa fa-lock"></i>
Secure Payment
</h1>

<p>
Complete your bike booking payment safely
</p>

</div>

</section>

<!-- PAYMENT -->

<div class="container">

<div class="payment-card">

<div class="row">

<!-- LEFT SIDE -->

<div class="col-md-5">

<div class="payment-left">

<img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1); ?>"
class="payment-bike-img">

<h2 class="bike-title">
<?php echo htmlentities($result->VehiclesTitle); ?>
</h2>

<div class="price-tag">
₹<?php echo htmlentities($result->PricePerDay); ?>
/ Day
</div>

<div class="detail-box">

<h5>
Booking From
</h5>

<p>
<?php echo htmlentities($result->FromDate); ?>
</p>

</div>

<div class="detail-box">

<h5>
Booking To
</h5>

<p>
<?php echo htmlentities($result->ToDate); ?>
</p>

</div>

<div class="detail-box">

<h5>
Transaction ID
</h5>

<p>
AUTO GENERATED
</p>

</div>

</div>

</div>

<!-- RIGHT SIDE -->

<div class="col-md-7">

<div class="payment-right">

<h2>
Payment Details
</h2>

<p class="secure-text">

<i class="fa fa-shield"></i>

100% Secure Payment Gateway

</p>

<form method="post">

<div class="form-group">

<label>
Select Payment Method
</label>

<select name="paymentmethod"
class="form-control"
required>

<option value="">
Choose Payment Method
</option>

<option value="UPI">
UPI Payment
</option>

<option value="Credit Card">
Credit / Debit Card
</option>

<option value="Net Banking">
Net Banking
</option>

<option value="Cash">
Cash On Pickup
</option>

</select>

</div>

<?php

$fromdate = strtotime($result->FromDate);
$todate = strtotime($result->ToDate);

$days = ($todate - $fromdate) / (60 * 60 * 24);

if($days <= 0)
{
    $days = 1;
}

$totalamount = $days * $result->PricePerDay;

?>

<div class="summary-box">

<div class="summary-item">

<h5>
Rental Days
</h5>

<p>
<?php echo htmlentities($days); ?> Day(s)
</p>

</div>

<div class="summary-item">

<h5>
Price Per Day
</h5>

<p>
₹<?php echo htmlentities($result->PricePerDay); ?>
</p>

</div>

<div class="summary-item total-box">

<h4>
Total Amount
</h4>

<p>
₹<?php echo htmlentities($totalamount); ?>
</p>

</div>

</div>

<br>

<button type="submit"
name="paynow"
class="btn-pay">

<i class="fa fa-lock"></i>

Pay ₹<?php echo htmlentities($totalamount); ?>

</button>

<div class="security-box">

<p>

<i class="fa fa-check-circle"></i>

SSL Secured Payment |
Instant Booking Confirmation

</p>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include('includes/footer.php');?>

<script src="assets/js/jquery.min.js"></script>

<script src="assets/js/bootstrap.min.js"></script>

</body>

</html>

<?php } ?>