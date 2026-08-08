<?php
session_start();
include('includes/config.php');

if(strlen($_SESSION['login'])==0)
{
header('location:index.php');
}
else
{

$msg="";
$error="";

if(isset($_POST['submit']))
{

$currentpassword=md5($_POST['currentpassword']);
$newpassword=md5($_POST['newpassword']);
$confirmpassword=md5($_POST['confirmpassword']);

$email=$_SESSION['login'];

if($newpassword!=$confirmpassword)
{
$error="New Password and Confirm Password do not match";
}
else
{

$sql ="SELECT Password FROM tblusers 
WHERE EmailId=:email AND Password=:currentpassword";

$query= $dbh -> prepare($sql);

$query-> bindParam(':email', $email, PDO::PARAM_STR);
$query-> bindParam(':currentpassword', $currentpassword, PDO::PARAM_STR);

$query-> execute();

$results = $query -> fetchAll(PDO::FETCH_OBJ);

if($query -> rowCount() > 0)
{

$con="UPDATE tblusers 
SET Password=:newpassword 
WHERE EmailId=:email";

$chngpwd1 = $dbh->prepare($con);

$chngpwd1->bindParam(':email',$email,PDO::PARAM_STR);
$chngpwd1->bindParam(':newpassword',$newpassword,PDO::PARAM_STR);

$chngpwd1->execute();

$msg="Password Changed Successfully";

}
else
{
$error="Current Password is wrong";
}

}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<title>Change Password</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<style>

body{
background:#f4f7fb;
font-family:Arial;
}

.card-box{
background:#fff;
padding:40px;
border-radius:15px;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
margin-top:60px;
}

.btn-save{
background:#007bff;
color:#fff;
padding:12px 25px;
border:none;
border-radius:8px;
}

.success{
background:#d4edda;
padding:12px;
margin-bottom:20px;
border-radius:5px;
}

.error{
background:#f8d7da;
padding:12px;
margin-bottom:20px;
border-radius:5px;
}

</style>

</head>

<body>

<?php include('includes/header.php');?>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card-box">

<h2>
<i class="fa fa-lock"></i>
Change Password
</h2>

<br>

<?php if($msg){ ?>
<div class="success">
<?php echo htmlentities($msg); ?>
</div>
<?php } ?>

<?php if($error){ ?>
<div class="error">
<?php echo htmlentities($error); ?>
</div>
<?php } ?>

<form method="post">

<div class="form-group">

<label>Current Password</label>

<input type="password"
name="currentpassword"
class="form-control"
required>

</div>

<div class="form-group">

<label>New Password</label>

<input type="password"
name="newpassword"
class="form-control"
required>

</div>

<div class="form-group">

<label>Confirm Password</label>

<input type="password"
name="confirmpassword"
class="form-control"
required>

</div>

<br>

<button type="submit"
name="submit"
class="btn-save">

Update Password

</button>

</form>

</div>

</div>

</div>

</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>

<?php } ?>