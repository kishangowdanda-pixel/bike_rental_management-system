<?php
require_once("includes/config.php");

error_reporting(0);

/* EMAIL AVAILABILITY CHECK */

if(!empty($_POST["emailid"]))
{

$email = trim($_POST["emailid"]);

/* VALIDATE EMAIL */

if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
{
?>

<style>

.email-alert{
    background:linear-gradient(135deg,#7f1d1d,#dc2626);
    color:#fff;
    padding:14px 18px;
    border-radius:14px;
    font-family:'Poppins',sans-serif;
    font-size:14px;
    margin-top:10px;
    display:flex;
    align-items:center;
    gap:10px;
    animation:slideFade 0.5s ease;
    box-shadow:0 10px 25px rgba(220,38,38,0.25);
}

.email-alert i{
    font-size:18px;
}

@keyframes slideFade{

from{
opacity:0;
transform:translateY(10px);
}

to{
opacity:1;
transform:translateY(0px);
}

}

</style>

<div class="email-alert">

<i class="fa fa-times-circle"></i>

Please enter a valid email address

</div>

<?php
}
else
{

$sql ="SELECT EmailId FROM tblusers WHERE EmailId=:email";

$query = $dbh->prepare($sql);

$query->bindParam(':email',$email,PDO::PARAM_STR);

$query->execute();

if($query->rowCount() > 0)
{
?>

<style>

.email-exists{
    background:linear-gradient(135deg,#991b1b,#ef4444);
    color:#fff;
    padding:14px 18px;
    border-radius:14px;
    font-family:'Poppins',sans-serif;
    font-size:14px;
    margin-top:10px;
    display:flex;
    align-items:center;
    gap:10px;
    animation:zoomIn 0.5s ease;
    box-shadow:0 10px 25px rgba(239,68,68,0.25);
}

.email-exists i{
    font-size:18px;
    animation:pulse 1s infinite;
}

@keyframes zoomIn{

from{
opacity:0;
transform:scale(0.9);
}

to{
opacity:1;
transform:scale(1);
}

}

@keyframes pulse{

0%{
transform:scale(1);
}

50%{
transform:scale(1.2);
}

100%{
transform:scale(1);
}

}

</style>

<div class="email-exists">

<i class="fa fa-envelope"></i>

Email already exists. Try another email address.

</div>

<script>
$('#submit').prop('disabled',true);
</script>

<?php
}
else
{
?>

<style>

.email-success{
    background:linear-gradient(135deg,#065f46,#10b981);
    color:#fff;
    padding:14px 18px;
    border-radius:14px;
    font-family:'Poppins',sans-serif;
    font-size:14px;
    margin-top:10px;
    display:flex;
    align-items:center;
    gap:10px;
    animation:successPop 0.5s ease;
    box-shadow:0 10px 25px rgba(16,185,129,0.25);
    position:relative;
    overflow:hidden;
}

.email-success:before{
    content:'';
    position:absolute;
    width:120px;
    height:120px;
    background:rgba(255,255,255,0.08);
    border-radius:50%;
    top:-40px;
    right:-40px;
}

.email-success i{
    font-size:18px;
    animation:tickMove 1s infinite;
}

@keyframes successPop{

from{
opacity:0;
transform:translateY(10px);
}

to{
opacity:1;
transform:translateY(0px);
}

}

@keyframes tickMove{

0%{
transform:translateY(0px);
}

50%{
transform:translateY(-3px);
}

100%{
transform:translateY(0px);
}

}

</style>

<div class="email-success">

<i class="fa fa-check-circle"></i>

Email available for registration

</div>

<script>
$('#submit').prop('disabled',false);
</script>

<?php
}
}
}
?>