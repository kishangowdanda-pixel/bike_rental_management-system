<?php
session_start();
include('includes/config.php');
error_reporting(0);

$booking = null;
$trackingHistory = [];
$error = "";
$success = "";

if(isset($_POST['track']))
{
    $tracking_id = trim($_POST['tracking_id']);

    if(empty($tracking_id))
    {
        $error = "Please enter Tracking ID.";
    }
    else
    {
        // Booking details
        $sql = "SELECT 
                    b.id AS booking_id,
                    b.TrackingNumber,
                    b.Status,
                    b.FromDate,
                    b.ToDate,
                    b.PostingDate,
                    v.VehiclesTitle,
                    v.Vimage1
                FROM tblbooking b
                JOIN tblvehicles v ON v.id = b.VehicleId
                WHERE b.TrackingNumber = :tracking_id
                LIMIT 1";

        $query = $dbh->prepare($sql);
        $query->bindParam(':tracking_id', $tracking_id, PDO::PARAM_STR);
        $query->execute();

        if($query->rowCount() > 0)
        {
            $booking = $query->fetch(PDO::FETCH_OBJ);

            // Tracking history
            $sql2 = "SELECT status, message, updated_on
                     FROM tracking_history
                     WHERE tracking_id = :tracking_id
                     ORDER BY updated_on ASC";

            $query2 = $dbh->prepare($sql2);
            $query2->bindParam(':tracking_id', $tracking_id, PDO::PARAM_STR);
            $query2->execute();
            $trackingHistory = $query2->fetchAll(PDO::FETCH_OBJ);

            $success = "Tracking Details Found!";
        }
        else
        {
            $error = "Invalid Tracking ID. Please check and try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Booking | Bike Rental Portal</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/font-awesome.min.css">

<style>
body{
    background:#f3f4f6;
    font-family: Arial, sans-serif;
}

.track-container{
    max-width: 1000px;
    margin: 40px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0px 4px 15px rgba(0,0,0,0.15);
}

.track-container h2{
    text-align: center;
    margin-bottom: 25px;
    font-weight: 700;
    color: #111827;
}

.track-form{
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.track-form input{
    width: 60%;
    min-width: 250px;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    outline: none;
}

.track-form button{
    padding: 12px 22px;
    border: none;
    border-radius: 10px;
    background: #2563eb;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

.track-form button:hover{
    background: #1d4ed8;
}

.alert-success{
    background: #dcfce7;
    padding: 12px;
    border-radius: 10px;
    color: #166534;
    margin-bottom: 15px;
}

.alert-danger{
    background: #fee2e2;
    padding: 12px;
    border-radius: 10px;
    color: #991b1b;
    margin-bottom: 15px;
}

.booking-box{
    background: #f9fafb;
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
    border: 1px solid #e5e7eb;
}

.booking-box h4{
    font-weight: 700;
    margin-bottom: 15px;
    color: #111827;
}

.booking-box p{
    margin: 8px 0;
    font-size: 15px;
}

.bike-img{
    width: 100%;
    max-width: 320px;
    border-radius: 10px;
    margin-top: 10px;
    border: 1px solid #ddd;
}

.status-badge{
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 600;
    display: inline-block;
    font-size: 13px;
}

.badge-requested{ background:#fef3c7; color:#92400e; }
.badge-approved{ background:#dbeafe; color:#1e40af; }
.badge-cancelled{ background:#fee2e2; color:#991b1b; }

table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

table th, table td{
    padding: 12px;
    border: 1px solid #e5e7eb;
}

table th{
    background: #f1f5f9;
    font-weight: bold;
}

#map{
    width:100%;
    height:450px;
    border-radius:12px;
    border:1px solid #ddd;
    margin-top:20px;
}

.map-info{
    margin-top:10px;
    padding:12px;
    border-radius:10px;
    background:#f1f5f9;
    font-weight:600;
}
</style>

</head>

<body>

<?php include('includes/header.php'); ?>

<div class="track-container">

    <h2>🚲 Track Your Bike Booking</h2>

    <?php if(!empty($success)) { ?>
        <div class="alert-success"><?php echo htmlentities($success); ?></div>
    <?php } ?>

    <?php if(!empty($error)) { ?>
        <div class="alert-danger"><?php echo htmlentities($error); ?></div>
    <?php } ?>

    <form method="post" class="track-form">
        <input type="text" name="tracking_id" placeholder="Enter Tracking ID (Example: BRMS2026000001)" required>
        <button type="submit" name="track">Track</button>
    </form>

    <?php if($booking != null) { ?>

        <div class="booking-box">
            <h4>Booking Information</h4>

            <p><b>Tracking ID:</b> <?php echo htmlentities($booking->TrackingNumber); ?></p>

            <p><b>Bike Name:</b> <?php echo htmlentities($booking->VehiclesTitle); ?></p>

            <p><b>Booking Date:</b> <?php echo htmlentities($booking->PostingDate); ?></p>

            <p><b>From Date:</b> <?php echo htmlentities($booking->FromDate); ?></p>

            <p><b>To Date:</b> <?php echo htmlentities($booking->ToDate); ?></p>

            <?php
            $badgeClass = "badge-requested";

            if($booking->Status == 1) $badgeClass = "badge-approved";
            if($booking->Status == 2) $badgeClass = "badge-cancelled";
            ?>

            <p><b>Current Status:</b>
                <span class="status-badge <?php echo $badgeClass; ?>">
                    <?php 
                        if($booking->Status == 0) echo "Pending";
                        if($booking->Status == 1) echo "Approved";
                        if($booking->Status == 2) echo "Cancelled";
                    ?>
                </span>
            </p>

            <?php if(!empty($booking->Vimage1)) { ?>
                <img src="admin/img/vehicleimages/<?php echo htmlentities($booking->Vimage1); ?>" class="bike-img" alt="Bike">
            <?php } ?>

        </div>

        <h4 style="margin-top:25px; font-weight:700;">📌 Tracking History</h4>

        <?php if(count($trackingHistory) > 0) { ?>

            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($trackingHistory as $row) { ?>
                        <tr>
                            <td><?php echo htmlentities($row->status); ?></td>
                            <td><?php echo htmlentities($row->message); ?></td>
                            <td><?php echo htmlentities($row->updated_on); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        <?php } else { ?>

            <div class="alert-danger" style="margin-top:15px;">
                No tracking updates available yet.
            </div>

        <?php } ?>

        <h4 style="margin-top:30px; font-weight:700;">🗺 Live Bike GPS Location</h4>

        <div id="map"></div>
        <div class="map-info" id="gpsinfo">Loading GPS location...</div>

        <script>
        let map;
        let marker;

        function initMap()
        {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: {lat: 12.9716, lng: 77.5946}
            });

            marker = new google.maps.Marker({
                position: {lat: 12.9716, lng: 77.5946},
                map: map,
                title: "Bike Location"
            });

            loadGPS();
            setInterval(loadGPS, 5000);
        }

        function loadGPS()
        {
            fetch("load-gps.php?tracking_id=<?php echo $booking->TrackingNumber; ?>")
                .then(res => res.json())
                .then(data => {

                    if(data.status === "success")
                    {
                        let pos = {
                            lat: parseFloat(data.lat),
                            lng: parseFloat(data.lng)
                        };

                        marker.setPosition(pos);
                        map.setCenter(pos);

                        document.getElementById("gpsinfo").innerHTML =
                            "📍 Latitude: " + data.lat +
                            " | Longitude: " + data.lng +
                            " | Speed: " + data.speed +
                            " km/h | Updated: " + data.updated_on;
                    }
                    else
                    {
                        document.getElementById("gpsinfo").innerHTML =
                            "❌ GPS data not available yet for this bike.";
                    }

                });
        }
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTRvrZPCfgeju9E7MHSs5AAb2nHYyFC1o&callback=initMap"></script>

    <?php } ?>

</div>

<?php include('includes/footer.php'); ?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

</body>
</html>