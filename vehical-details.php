<?php
session_start();
include('includes/config.php');
error_reporting(0);

if(isset($_POST['submit']))
{
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $message = $_POST['message'];
    $useremail = $_SESSION['login'];
    $status = 0;
    $vhid = $_GET['vhid'];

    // Date Validation
    if(strtotime($todate) < strtotime($fromdate))
    {
        echo "<script>alert('To Date must be greater than From Date');</script>";
    }
    else
    {

        $sql = "INSERT INTO tblbooking
        (userEmail,VehicleId,FromDate,ToDate,message,Status)
        VALUES
        (:useremail,:vhid,:fromdate,:todate,:message,:status)";

        $query = $dbh->prepare($sql);

        $query->bindParam(':useremail',$useremail,PDO::PARAM_STR);
        $query->bindParam(':vhid',$vhid,PDO::PARAM_STR);
        $query->bindParam(':fromdate',$fromdate,PDO::PARAM_STR);
        $query->bindParam(':todate',$todate,PDO::PARAM_STR);
        $query->bindParam(':message',$message,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);

        $query->execute();

        $lastInsertId = $dbh->lastInsertId();

        if($lastInsertId)
        {
            echo "<script>
            alert('Booking Successful! Proceed to Payment');
            window.location='payment.php?bid=".$lastInsertId."';
            </script>";
        }
        else
        {
            echo "<script>alert('Something went wrong. Please try again');</script>";
        }
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Bike Rental Portal | Bike Details</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/styles.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<style>

body{
    background:#f4f7fb;
    font-family:'Segoe UI',sans-serif;
    color:#333;
}

/* MAIN SECTION */
.main-section{
    padding:50px 0;
}

/* HERO IMAGE */
.bike-hero{
    position:relative;
    overflow:hidden;
    border-radius:20px;
    box-shadow:0 8px 30px rgba(0,0,0,0.15);
    margin-bottom:30px;
    background:#fff;
}

.bike-hero img{
    width:100%;
    height:500px;
    object-fit:cover;
    transition:0.5s;
}

.bike-hero:hover img{
    transform:scale(1.03);
}

.hero-overlay{
    position:absolute;
    left:0;
    right:0;
    bottom:0;
    padding:40px;
    background:linear-gradient(to top,rgba(0,0,0,0.85),transparent);
    color:#fff;
}

.hero-overlay h1{
    font-size:42px;
    font-weight:700;
    margin-bottom:10px;
}

.hero-overlay p{
    font-size:18px;
}

/* PRICE BOX */
.price-box{
    background:#fff;
    padding:25px;
    border-radius:15px;
    margin-bottom:25px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.price-box h2{
    color:#28a745;
    font-size:40px;
    font-weight:700;
    margin:0;
}

.price-box span{
    color:#666;
    font-size:18px;
}

/* BADGES */
.bike-badges{
    margin-top:15px;
}

.bike-badges span{
    display:inline-block;
    background:#eef4ff;
    color:#0056b3;
    padding:8px 15px;
    border-radius:30px;
    margin-right:10px;
    margin-bottom:10px;
    font-size:14px;
    font-weight:600;
}

/* OVERVIEW */
.overview-section{
    background:#fff;
    border-radius:15px;
    padding:30px;
    margin-top:25px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.overview-section h3{
    font-size:28px;
    margin-bottom:20px;
    font-weight:700;
}

/* FEATURES */
.feature-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-top:30px;
}

.feature-item{
    background:#fff;
    padding:25px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
    transition:0.3s;
}

.feature-item:hover{
    transform:translateY(-5px);
}

.feature-item i{
    font-size:40px;
    color:#007bff;
    margin-bottom:15px;
}

.feature-item h4{
    font-size:20px;
    font-weight:700;
    margin-bottom:10px;
}

/* BOOKING BOX */
.booking-box{
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 8px 30px rgba(0,0,0,0.08);
    position:sticky;
    top:20px;
}

.booking-box h2{
    text-align:center;
    font-size:28px;
    margin-bottom:25px;
    font-weight:700;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    font-weight:600;
    margin-bottom:8px;
}

.form-control{
    height:50px;
    border-radius:10px;
    border:1px solid #ddd;
    box-shadow:none;
}

.form-control:focus{
    border-color:#007bff;
    box-shadow:0 0 8px rgba(0,123,255,0.2);
}

textarea.form-control{
    height:120px;
    resize:none;
}

/* BUTTON */
.btn-book{
    width:100%;
    background:linear-gradient(135deg,#007bff,#0056b3);
    color:#fff;
    border:none;
    padding:14px;
    border-radius:10px;
    font-size:18px;
    font-weight:600;
    transition:0.3s;
}

.btn-book:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(0,123,255,0.3);
    color:#fff;
}

/* LOGIN BUTTON */
.btn-login{
    display:block;
    width:100%;
    background:#dc3545;
    color:#fff;
    text-align:center;
    padding:14px;
    border-radius:10px;
    font-size:18px;
    font-weight:600;
    text-decoration:none;
}

.btn-login:hover{
    background:#b02a37;
    color:#fff;
    text-decoration:none;
}

/* RESPONSIVE */
@media(max-width:768px){

.hero-overlay h1{
    font-size:28px;
}

.feature-grid{
    grid-template-columns:1fr;
}

.bike-hero img{
    height:300px;
}

}

</style>

</head>

<body>

<?php include('includes/header.php');?>

<?php
$vhid = intval($_GET['vhid']);

$sql = "SELECT tblvehicles.*,tblbrands.BrandName
FROM tblvehicles
JOIN tblbrands ON tblbrands.id=tblvehicles.VehiclesBrand
WHERE tblvehicles.id=:vhid";

$query = $dbh->prepare($sql);

$query->bindParam(':vhid',$vhid,PDO::PARAM_STR);

$query->execute();

$results = $query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
foreach($results as $result)
{
?>

<section class="main-section">

<div class="container">

<div class="row">

<!-- LEFT SIDE -->
<div class="col-md-8">

<!-- HERO IMAGE -->
<div class="bike-hero">

<img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1);?>">

<div class="hero-overlay">

<h1>
<?php echo htmlentities($result->BrandName);?>
-
<?php echo htmlentities($result->VehiclesTitle);?>
</h1>

<p>
Premium Bike Rental Service
</p>

<div class="bike-badges">

<span>
<i class="fa fa-check-circle"></i>
Verified Bike
</span>

<span>
<i class="fa fa-star"></i>
Top Rated
</span>

<span>
<i class="fa fa-shield"></i>
Secure Booking
</span>

</div>

</div>

</div>

<!-- PRICE -->
<div class="price-box">

<h2>
₹<?php echo htmlentities($result->PricePerDay);?>
</h2>

<span>Per Day</span>

</div>

<!-- OVERVIEW -->
<div class="overview-section">

<h3>
<i class="fa fa-info-circle"></i>
Bike Overview
</h3>

<p>
<?php echo htmlentities($result->VehiclesOverview);?>
</p>

</div>

<!-- FEATURES -->
<div class="feature-grid">

<div class="feature-item">

<i class="fa fa-motorcycle"></i>

<h4>Premium Bikes</h4>

<p>Well maintained rental bikes</p>

</div>

<div class="feature-item">

<i class="fa fa-shield"></i>

<h4>Safe Ride</h4>

<p>100% secure and trusted rides</p>

</div>

<div class="feature-item">

<i class="fa fa-headphones"></i>

<h4>24/7 Support</h4>

<p>Customer support anytime</p>

</div>

</div>

</div>

<!-- RIGHT SIDE -->
<div class="col-md-4">

<div class="booking-box">

<h2>
<i class="fa fa-calendar-check-o"></i>
Book This Bike
</h2>

<form method="post">

<div class="form-group">

<label>
<i class="fa fa-calendar"></i>
From Date
</label>

<input type="date"
name="fromdate"
class="form-control"
required>

</div>

<div class="form-group">

<label>
<i class="fa fa-calendar"></i>
To Date
</label>

<input type="date"
name="todate"
class="form-control"
required>

</div>

<div class="form-group">

<label>
<i class="fa fa-envelope"></i>
Message
</label>

<textarea name="message"
class="form-control"
placeholder="Enter your booking message"
required></textarea>

</div>

<?php if($_SESSION['login']) { ?>

<button type="submit"
name="submit"
class="btn-book">

<i class="fa fa-check-circle"></i>
Book Now

</button>

<?php } else { ?>

<a href="#loginform"
data-toggle="modal"
data-dismiss="modal"
class="btn-login">

<i class="fa fa-user"></i>
Login To Continue

</a>

<?php } ?>

</form>

</div>

</div>

</div>

</div>

</section>

<?php }} ?>

<?php include('includes/footer.php');?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>