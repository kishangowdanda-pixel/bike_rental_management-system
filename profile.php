<?php
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['login'])==0)
{
    header('location:index.php');
}
else
{

if(isset($_POST['updateprofile']))
{
    $name=$_POST['fullname'];
    $mobileno=$_POST['mobilenumber'];
    $dob=$_POST['dob'];
    $adress=$_POST['address'];
    $city=$_POST['city'];
    $country=$_POST['country'];
    $email=$_SESSION['login'];

    $sql="UPDATE tblusers 
    SET FullName=:name,
    ContactNo=:mobileno,
    dob=:dob,
    Address=:adress,
    City=:city,
    Country=:country 
    WHERE EmailId=:email";

    $query = $dbh->prepare($sql);

    $query->bindParam(':name',$name,PDO::PARAM_STR);
    $query->bindParam(':mobileno',$mobileno,PDO::PARAM_STR);
    $query->bindParam(':dob',$dob,PDO::PARAM_STR);
    $query->bindParam(':adress',$adress,PDO::PARAM_STR);
    $query->bindParam(':city',$city,PDO::PARAM_STR);
    $query->bindParam(':country',$country,PDO::PARAM_STR);
    $query->bindParam(':email',$email,PDO::PARAM_STR);

    $query->execute();

    $msg="Profile Updated Successfully";
}
?>

<!DOCTYPE HTML>
<html lang="en">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>Bike Rental Portal | My Profile</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/styles.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

<style>

body{
    background:#f4f7fb;
    font-family:'Poppins',sans-serif;
}

/* HEADER */
.profile-banner{
    background:linear-gradient(135deg,#007bff,#003b8e);
    padding:70px 0;
    color:#fff;
    text-align:center;
}

.profile-banner h1{
    font-size:42px;
    font-weight:700;
}

.profile-banner p{
    font-size:18px;
    margin-top:10px;
    opacity:0.9;
}

/* QUICK ACTIONS */
.quick-actions{
    margin-top:-35px;
    margin-bottom:40px;
}

.action-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.action-card{
    background:#fff;
    padding:25px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
    transition:0.3s;
    text-decoration:none;
}

.action-card:hover{
    transform:translateY(-5px);
    text-decoration:none;
}

.action-card i{
    font-size:42px;
    color:#007bff;
    margin-bottom:15px;
}

.action-card h4{
    color:#222;
    font-weight:700;
}

.action-card p{
    color:#666;
    font-size:14px;
}

/* PROFILE CARD */
.profile-card{
    background:#fff;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    margin-bottom:50px;
}

/* SIDEBAR */
.profile-sidebar{
    background:linear-gradient(180deg,#0d6efd,#003b8e);
    color:#fff;
    padding:40px 25px;
    height:100%;
    text-align:center;
}

.profile-sidebar img{
    width:130px;
    height:130px;
    border-radius:50%;
    border:5px solid rgba(255,255,255,0.3);
    margin-bottom:20px;
}

.profile-sidebar h3{
    font-size:28px;
    font-weight:700;
}

.profile-sidebar p{
    opacity:0.9;
}

/* PROFILE INFO */
.profile-info{
    margin-top:25px;
    text-align:left;
}

.detail-box{
    background:rgba(255,255,255,0.12);
    padding:15px;
    border-radius:15px;
    margin-bottom:15px;
}

.detail-box i{
    width:25px;
}

/* PROFILE STATS */
.profile-stats{
    margin-top:30px;
}

.stat-card{
    background:rgba(255,255,255,0.12);
    padding:18px;
    border-radius:15px;
    margin-bottom:15px;
    text-align:center;
}

.stat-card h4{
    font-size:28px;
    font-weight:700;
    margin-bottom:5px;
}

.stat-card p{
    margin:0;
    font-size:14px;
}

/* COMPLETION */
.completion-box{
    background:rgba(255,255,255,0.12);
    padding:20px;
    border-radius:15px;
    margin-top:25px;
}

.progress{
    height:20px;
    border-radius:30px;
    overflow:hidden;
    background:rgba(255,255,255,0.2);
}

.progress-bar{
    background:#28a745;
    line-height:20px;
    font-size:12px;
    font-weight:600;
}

/* FORM */
.profile-form{
    padding:40px;
}

.profile-form h2{
    font-size:32px;
    font-weight:700;
    margin-bottom:30px;
}

.form-group{
    margin-bottom:22px;
}

.form-group label{
    font-weight:600;
    margin-bottom:8px;
}

.form-control{
    height:50px;
    border-radius:12px;
    border:1px solid #ddd;
    box-shadow:none;
}

.form-control:focus{
    border-color:#007bff;
    box-shadow:0 0 10px rgba(0,123,255,0.2);
}

textarea.form-control{
    height:120px;
    resize:none;
}

/* SUCCESS */
.success-box{
    background:#d4edda;
    color:#155724;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
}

/* BUTTON */
.update-btn{
    background:linear-gradient(135deg,#007bff,#0056b3);
    border:none;
    color:#fff;
    padding:14px 35px;
    border-radius:12px;
    font-size:18px;
    font-weight:600;
    transition:0.3s;
}

.update-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(0,123,255,0.3);
}

/* ANIMATION */
.profile-card,
.action-card,
.detail-box{
    animation:fadeInUp 0.6s ease;
}

@keyframes fadeInUp{

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

.action-grid{
    grid-template-columns:1fr;
}

.profile-form{
    padding:25px;
}

.profile-banner h1{
    font-size:30px;
}

}

</style>

</head>

<body>

<?php include('includes/header.php');?>

<!-- BANNER -->
<section class="profile-banner">

<div class="container">

<h1>
<i class="fa fa-user-circle"></i>
My Profile
</h1>

<p>
Manage your personal account information
</p>

</div>

</section>

<!-- QUICK ACTIONS -->
<section class="quick-actions">

<div class="container">

<div class="action-grid">

<a href="my-booking.php" class="action-card">

<i class="fa fa-motorcycle"></i>

<h4>My Bookings</h4>

<p>View all bookings</p>

</a>

<a href="change-password.php" class="action-card">

<i class="fa fa-lock"></i>

<h4>Security</h4>

<p>Change password</p>

</a>

<a href="payment-history.php" class="action-card">

<i class="fa fa-credit-card"></i>

<h4>Payments</h4>

<p>Payment history</p>

</a>

<a href="logout.php" class="action-card">

<i class="fa fa-sign-out"></i>

<h4>Logout</h4>

<p>Secure logout</p>

</a>

</div>

</div>

</section>

<?php
$useremail=$_SESSION['login'];

$sql = "SELECT * FROM tblusers WHERE EmailId=:useremail";

$query = $dbh->prepare($sql);

$query->bindParam(':useremail',$useremail,PDO::PARAM_STR);

$query->execute();

$results=$query->fetchAll(PDO::FETCH_OBJ);

if($query->rowCount() > 0)
{
foreach($results as $result)
{
?>

<section>

<div class="container">

<div class="profile-card">

<div class="row">

<!-- SIDEBAR -->
<div class="col-md-4">

<div class="profile-sidebar">

<img src="https://ui-avatars.com/api/?name=<?php echo urlencode($result->FullName); ?>&background=ffffff&color=007bff&size=200">

<h3>
<?php echo htmlentities($result->FullName);?>
</h3>

<p>
<?php echo htmlentities($result->EmailId);?>
</p>

<div class="profile-info">

<div class="detail-box">

<p>
<i class="fa fa-phone"></i>
<?php echo htmlentities($result->ContactNo);?>
</p>

</div>

<div class="detail-box">

<p>
<i class="fa fa-map-marker"></i>
<?php echo htmlentities($result->City);?>
</p>

</div>

<div class="detail-box">

<p>
<i class="fa fa-globe"></i>
<?php echo htmlentities($result->Country);?>
</p>

</div>

<div class="detail-box">

<p>
<i class="fa fa-calendar"></i>
<?php echo htmlentities($result->RegDate);?>
</p>

</div>

</div>

<!-- STATS -->
<div class="profile-stats">

<div class="stat-card">

<h4>
<?php
$sql1 = "SELECT id FROM tblbooking WHERE userEmail=:email";
$query1 = $dbh->prepare($sql1);
$query1->bindParam(':email',$useremail,PDO::PARAM_STR);
$query1->execute();
echo $query1->rowCount();
?>
</h4>

<p>Total Bookings</p>

</div>

<div class="stat-card">

<h4>
<?php
$sql2 = "SELECT id FROM tblbooking 
WHERE userEmail=:email AND Status=1";

$query2 = $dbh->prepare($sql2);
$query2->bindParam(':email',$useremail,PDO::PARAM_STR);
$query2->execute();
echo $query2->rowCount();
?>
</h4>

<p>Confirmed</p>

</div>

<div class="stat-card">

<h4>
<?php
$sql3 = "SELECT id FROM tblbooking 
WHERE userEmail=:email AND Status=0";

$query3 = $dbh->prepare($sql3);
$query3->bindParam(':email',$useremail,PDO::PARAM_STR);
$query3->execute();
echo $query3->rowCount();
?>
</h4>

<p>Pending</p>

</div>

</div>

<!-- PROFILE COMPLETION -->
<div class="completion-box">

<h4>Profile Completion</h4>

<div class="progress">

<div class="progress-bar" 
role="progressbar" 
style="width:90%">

90%

</div>

</div>

<p style="margin-top:10px;">
Complete profile improves booking experience
</p>

</div>

</div>

</div>

<!-- FORM -->
<div class="col-md-8">

<div class="profile-form">

<h2>
<i class="fa fa-edit"></i>
Update Profile
</h2>

<?php if($msg){ ?>

<div class="success-box">

<i class="fa fa-check-circle"></i>
<?php echo htmlentities($msg); ?>

</div>

<?php } ?>

<form method="post">

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Full Name</label>

<input type="text"
name="fullname"
class="form-control"
value="<?php echo htmlentities($result->FullName);?>"
required>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Email Address</label>

<input type="email"
class="form-control"
value="<?php echo htmlentities($result->EmailId);?>"
readonly>

</div>

</div>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Phone Number</label>

<input type="text"
name="mobilenumber"
class="form-control"
value="<?php echo htmlentities($result->ContactNo);?>"
required>

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>Date of Birth</label>

<input type="date"
name="dob"
class="form-control"
value="<?php echo htmlentities($result->dob);?>">

</div>

</div>

</div>

<div class="form-group">

<label>Address</label>

<textarea name="address"
class="form-control"><?php echo htmlentities($result->Address);?></textarea>

</div>

<div class="row">

<div class="col-md-6">

<div class="form-group">

<label>Country</label>

<input type="text"
name="country"
class="form-control"
value="<?php echo htmlentities($result->Country);?>">

</div>

</div>

<div class="col-md-6">

<div class="form-group">

<label>City</label>

<input type="text"
name="city"
class="form-control"
value="<?php echo htmlentities($result->City);?>">

</div>

</div>

</div>

<button type="submit"
name="updateprofile"
class="update-btn">

<i class="fa fa-save"></i>
Save Changes

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</section>

<?php }} ?>

<?php include('includes/footer.php');?>

<div id="back-top" class="back-top">
<a href="#top">
<i class="fa fa-angle-up"></i>
</a>
</div>

<?php include('includes/login.php');?>
<?php include('includes/registration.php');?>
<?php include('includes/forgotpassword.php');?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>

</body>
</html>

<?php } ?>