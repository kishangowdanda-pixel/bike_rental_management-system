<?php
session_start();
include('includes/config.php');
error_reporting(0);

// India timezone
date_default_timezone_set("Asia/Kolkata");

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
<title>Payment History | Bike Rental</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    background: radial-gradient(circle at top left,#0b1224,#020617,#050b1c);
    font-family:'Poppins',sans-serif;
    overflow-x:hidden;
    color:#fff;
}

/* HEADER */
.top-header{
    width:100%;
    background:rgba(2,8,23,0.92);
    border-bottom:1px solid rgba(255,255,255,0.08);
    padding:14px 0;
    position:sticky;
    top:0;
    z-index:999;
    backdrop-filter:blur(12px);
}

.header-container{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:nowrap;
}

/* LOGO */
.logo-box{
    display:flex;
    align-items:center;
    gap:12px;
}

.logo-box img{
    height:55px;
}

.logo-text{
    font-size:26px;
    font-weight:700;
    color:#fff;
    margin:0;
}

.logo-text span{
    color:#38bdf8;
}

/* MENU */
.nav-menu{
    display:flex;
    align-items:center;
    gap:28px;
}

.nav-menu a{
    color:#e2e8f0;
    font-size:15px;
    font-weight:500;
    text-decoration:none;
    transition:0.3s;
    position:relative;
}

.nav-menu a:hover{
    color:#38bdf8;
}

.nav-menu a::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-6px;
    width:0;
    height:2px;
    background:#38bdf8;
    transition:0.3s;
}

.nav-menu a:hover::after{
    width:100%;
}

/* CONTACT */
.contact-box{
    display:flex;
    align-items:center;
    gap:25px;
}

.contact-item{
    display:flex;
    align-items:center;
    gap:8px;
    white-space:nowrap;
    font-size:14px;
    color:#cbd5e1;
}

.contact-item i{
    color:#38bdf8;
}

/* PAGE HEADER */
.page-header{
    padding:80px 0 120px;
    text-align:center;
    position:relative;
}

.page-header h1{
    font-size:52px;
    font-weight:800;
    letter-spacing:1px;
}

.page-header p{
    color:#cbd5e1;
    margin-top:12px;
    font-size:16px;
}

/* PAYMENT TABLE CARD */
.history-box{
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.08);
    backdrop-filter:blur(16px);
    margin-top:-70px;
    border-radius:28px;
    padding:30px;
    box-shadow:0 25px 60px rgba(0,0,0,0.5);
    margin-bottom:70px;
    overflow-x:auto;
}

.history-title{
    font-size:22px;
    font-weight:800;
    margin-bottom:20px;
    color:#fff;
}

/* TABLE */
.table{
    margin:0;
    border-radius:18px;
    overflow:hidden;
}

.table thead{
    background:linear-gradient(135deg,#2563eb,#7c3aed);
    color:#fff;
}

.table thead th{
    padding:16px;
    border:none;
    font-size:14px;
    letter-spacing:0.3px;
}

.table tbody td{
    padding:16px;
    color:#e2e8f0;
    border-color:rgba(255,255,255,0.08);
    vertical-align:middle;
    font-size:14px;
}

.table tbody tr{
    background:rgba(255,255,255,0.03);
    transition:0.3s;
}

.table tbody tr:hover{
    background:rgba(59,130,246,0.18);
}

/* BADGES */
.badge-paid{
    background:rgba(34,197,94,0.18);
    border:1px solid rgba(34,197,94,0.4);
    padding:7px 14px;
    border-radius:50px;
    color:#22c55e;
    font-size:12px;
    font-weight:700;
}

.badge-failed{
    background:rgba(239,68,68,0.15);
    border:1px solid rgba(239,68,68,0.4);
    padding:7px 14px;
    border-radius:50px;
    color:#ef4444;
    font-size:12px;
    font-weight:700;
}

/* EMPTY */
.empty{
    text-align:center;
    padding:70px 20px;
    color:#cbd5e1;
}

.empty i{
    font-size:70px;
    color:#38bdf8;
    margin-bottom:18px;
}

.empty h3{
    font-size:28px;
    font-weight:800;
    color:#fff;
}

.empty p{
    color:#cbd5e1;
    margin-top:10px;
}

/* RESPONSIVE */
@media(max-width:991px){
    .header-container{
        flex-direction:column;
        gap:15px;
    }
    .nav-menu{
        flex-wrap:wrap;
        justify-content:center;
    }
    .contact-box{
        flex-wrap:wrap;
        justify-content:center;
    }
}

@media(max-width:768px){
    .page-header h1{
        font-size:34px;
    }
}
</style>

</head>

<body>

<!-- HEADER -->
<div class="top-header">
    <div class="container">
        <div class="header-container">

            <div class="logo-box">
                <img src="assets/images/logo.png">
                <h2 class="logo-text">Bike <span>Rental</span></h2>
            </div>

            <div class="nav-menu">
                <a href="index.php">Home</a>
                <a href="car-listing.php">Bike Listing</a>
                <a href="my-booking.php">Bookings</a>
                <a href="payment-history.php">Payments</a>
                <a href="logout.php">Logout</a>
            </div>

            <div class="contact-box">
                <div class="contact-item">
                    <i class="fa fa-envelope"></i>
                    <span>bikerental@gmail.com</span>
                </div>
                <div class="contact-item">
                    <i class="fa fa-phone"></i>
                    <span>+91-6360764427</span>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- PAGE HEADER -->
<section class="page-header">
    <div class="container">
        <h1><i class="fa fa-credit-card"></i> Payment History</h1>
        <p>View all your successful payments with transaction details</p>
    </div>
</section>

<!-- PAYMENT TABLE -->
<div class="container">
    <div class="history-box">

        <div class="history-title">
            <i class="fa fa-list"></i> Your Payment Transactions
        </div>

        <?php
        $email = $_SESSION['login'];

        $sql="SELECT * FROM tblpayments
              WHERE UserEmail=:email
              ORDER BY id DESC";

        $query=$dbh->prepare($sql);
        $query->bindParam(':email',$email,PDO::PARAM_STR);
        $query->execute();

        $results=$query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount()>0)
        {
        ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Transaction ID</th>
                    <th>Booking ID</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Payment Date & Time</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $cnt=1;
            foreach($results as $row)
            {
                $payTime = $row->PostingDate;

                // FIX if PostingDate is empty or invalid
                if(empty($payTime) || $payTime=="0000-00-00 00:00:00"){
                    $payTime = date("Y-m-d H:i:s");
                }
            ?>
                <tr>
                    <td><?php echo htmlentities($cnt); ?></td>

                    <td style="font-weight:700;color:#93c5fd;">
                        <?php echo htmlentities($row->TransactionId); ?>
                    </td>

                    <td style="font-weight:700;">
                        BK-<?php echo htmlentities($row->BookingId); ?>
                    </td>

                    <td style="font-weight:800;color:#38bdf8;">
                        ₹<?php echo htmlentities($row->Amount); ?>
                    </td>

                    <td>
                        <?php echo htmlentities($row->PaymentMethod); ?>
                    </td>

                    <td>
                        <?php if(strtolower($row->PaymentStatus)=="paid"){ ?>
                            <span class="badge-paid">
                                <i class="fa fa-check-circle"></i>
                                Paid
                            </span>
                        <?php } else { ?>
                            <span class="badge-failed">
                                <i class="fa fa-times-circle"></i>
                                <?php echo htmlentities($row->PaymentStatus); ?>
                            </span>
                        <?php } ?>
                    </td>

                    <td style="font-weight:700;">
                        <?php echo date("d M Y, h:i:s A", strtotime($payTime)); ?>
                    </td>

                </tr>
            <?php
            $cnt++;
            }
            ?>
            </tbody>
        </table>

        <?php } else { ?>

        <div class="empty">
            <i class="fa fa-folder-open"></i>
            <h3>No Payment History Found</h3>
            <p>You have not made any payment yet.</p>
        </div>

        <?php } ?>

    </div>
</div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>