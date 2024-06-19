<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
// adminLogin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php require('../admin/inc/links.php') ?>
    <style>
        #dashboard-menu {
            height: 100%;
            position: fixed;
            z-index: 11;
        }

        @media screen and (max-width: 992px) {

            #dashboard-menu {
                height: auto;
                width: 100%;
            }

            #main-content {
                margin-top: 60px;
            }
        }
    </style>
</head>


<body class="bg-light">

    <?php
    require('../admin/inc/header_manager.php');
    session_start();

    $is_shutdown = mysqli_fetch_assoc(mysqli_query($con, "SELECT `shutdown` FROM `settings`"));

    $current_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT
    COUNT(CASE WHEN booking_status='booked' AND arrival=0 THEN 1 END) AS `new_bookings`,
    COUNT(CASE WHEN booking_status='cancelled' AND refund=0 THEN 1 END) AS `refund_bookings` 
    FROM `booking_order` WHERE `hotel_id`='$_SESSION[hotelId]'"));

    // $unread_queries = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(id) AS `count` 
    // FROM `user_queries` WHERE `seen`=0"));

    // $reviews = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(id) AS `count` 
    // FROM `rating_review`"));

    // $current_users = mysqli_fetch_assoc(mysqli_query($con, "SELECT
    // COUNT(id) AS `total`,
    // COUNT(CASE WHEN `status`=1 THEN 1 END) AS `active`,
    // COUNT(CASE WHEN `status`=0 THEN 1 END) AS `inactive`,
    // COUNT(CASE WHEN `is_verified`=0 THEN 1 END) AS `unverified`
    // FROM `user_cred`"));

    // $hotel = mysqli_fetch_assoc(mysqli_query($con, "SELECT
    // COUNT(id) AS `total`,
    // COUNT(CASE WHEN `status`=1 AND `removed`=0 THEN 1 END) AS `active`,
    // COUNT(CASE WHEN `status`=0 AND `removed`=0 THEN 1 END) AS `inactive`
    // FROM `hotel`"));

    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-4">

                    <h3>DASHBOARD</h3>
                    <?php
                    if ($is_shutdown['shutdown']) {
                        echo <<<data
                            <h6 class="badge bg-danger py-2 px-3 rounded">Shutdown mode is active</h6>
                            data;
                    }
                    ?>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3 mb-4">
                        <a href="new_bookings.php" class="text-decoration-none">
                            <div class="card text-center text-success p-1">
                                <h5>New Bookings : <?php echo $current_bookings['new_bookings']; ?></h5>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-4">
                        <a href="refund_bookings.php" class="text-decoration-none">
                            <div class="card text-center text-warning p-1">
                                <h5>Refund Bookings :<?php echo $current_bookings['refund_bookings']; ?></h5>

                            </div>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4 mt-2">
                    <h3>Booking Analytics</h3>
                    <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytic(this.value)">
                        <option value="1">Past 1 Week</option>
                        <option value="2">Past 30 Days</option>
                        <option value="3">Past 90 Days</option>
                        <option value="4">All Time</option>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-1">
                            <h6>Total Bookings</h6>
                            <h1 class="mt-2 mb-0" id="total_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="total_amt">0 VND</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-1">
                            <h6>Active Bookings</h6>
                            <h1 class="mt-2 mb-0" id="active_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="active_amt">0 VND</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-1">
                            <h6>Payment Failed</h6>
                            <h1 class="mt-2 mb-0" id="payment_failed_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="payment_failed_amt">0 VND</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-1">
                            <h6>Cancel Bookings</h6>
                            <h1 class="mt-2 mb-0" id="cancelled_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="cancelled_amt">0 VND</h4>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card text-center text-success p-1">
                            <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card text-center text-success p-1">
                            <div id="chartPrice" style="height: 370px; width: 100%;"></div>
                        </div>
                    </div>
                    <h3>Analysis of bookings for the year by month</h3>

                    <div class="card text-center p-3 mt-3">
                        <form method="post" action="" id="yearForm">
                            <label for="year">Enter Year:</label>
                            <input type="number" id="year" name="year" value="<?php echo date('Y'); ?>" required>
                            <input type="submit" value="Submit">
                        </form>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card text-center text-danger p-1">
                                    <div id="chartyear" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-center text-danger p-1">
                                    <div id="chartyearprice" style="height: 370px; width: 100%;"></div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>


            </div>
        </div>
    </div>
    <?php require('../admin/inc/scripts.php') ?>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="./scripts/dashboard_manager.js"></script>

    </script>
</body>

</html>