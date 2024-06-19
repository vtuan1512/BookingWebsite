<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
adminLogin();
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
    require('../admin/inc/header.php');
    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3 style="color: brown;">STATISTICAL</h3>
                    <form id="exportForm" method="post">
                        <input type="submit" class="btn btn-success" value="Export to Excel">
                    </form>
                </div>
                <div class="row mb-4">
                    <h4>Please choose the time period you want to view</h4>
                    <div class="d-flex align-items-center mt-4 mb-4" style="gap: 10px;">
                        <h6 style="margin: 0;">FROM</h6>
                        <input name="from_date" id="from_date" type="date" required style="flex: 1; max-width: 150px;">
                        <h6 style="margin: 0;">TO</h6>
                        <input name="to_date" id="to_date" type="date" required style="flex: 1; max-width: 150px;">
                        <button id="viewBookingsBtn" class="btn btn-primary">View Bookings</button>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-2 mb-4">
                        <div class="card text-center text-success p-1">
                            <h5 id="total">Total Bookings: </h5>
                        </div>
                    </div>
                    <div class="col-md-2 mb-4">

                        <div class="card text-center text-info p-1">
                            <h5 id="active">Active Bookings :</h5>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">

                        <div class="card text-center text-warning p-1">
                            <h5 id="failed">Payment Failed Bookings :</h5>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">

                        <div class="card text-center text-danger p-1">
                            <h5 id="cancelled">Cancelled Bookings :</h5>
                        </div>
                    </div>
                    <div class="col-md-2 mb-4">
                        <div class="card text-center text-success p-1">
                            <h5 id="income">Income :</h5>
                        </div>
                    </div>
                </div>


                <div class="card text-center p-4 mt-3">
                    <div id="bookings-data"></div>
                </div>
                <nav>
                    <ul class="pagination mt-3" id="table-pagination">
                    </ul>
                </nav>
            </div>
        </div>
        <?php require('../admin/inc/scripts.php') ?>
        <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
        <script>
            document.getElementById('viewBookingsBtn').addEventListener('click', function(e) {
                e.preventDefault();

                const fromDate = document.getElementById('from_date').value;
                const toDate = document.getElementById('to_date').value;

                if (!fromDate || !toDate) {
                    alert('error', 'Please select both dates.');
                    return;
                }
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'ajax/fetch_bookings.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.bookings) {
                            const bookingsData = response.bookings;
                            let bookingsHTML = "<table class='table'>";
                            bookingsHTML += "<thead><tr><th>STT</th><th>Order_id</th><th>User_id</th><th>Room_id</th><th>Hotel_id</th><th>Check_in</th><th>Check_out</th><th>Booking Status</th><th>Date & Time </th></tr></thead><tbody>";
                            bookingsData.forEach(function(booking, index) {
                                bookingsHTML += "<tr>";
                                bookingsHTML += "<td>" + (index + 1) + "</td>";
                                bookingsHTML += "<td>" + booking.order_id + "</td>";
                                bookingsHTML += "<td>" + booking.user_id + "</td>";
                                bookingsHTML += "<td>" + booking.room_id + "</td>";
                                bookingsHTML += "<td>" + booking.hotel_id + "</td>";
                                bookingsHTML += "<td>" + booking.check_in + "</td>";
                                bookingsHTML += "<td>" + booking.check_out + "</td>";
                                bookingsHTML += "<td>" + booking.booking_status + "</td>";
                                bookingsHTML += "<td>" + booking.date_time + "</td>";
                                bookingsHTML += "</tr>";
                            });
                            bookingsHTML += "</tbody></table>";
                            document.getElementById('bookings-data').innerHTML = bookingsHTML;
                            const stats = response.statistics;
                            document.getElementById('total').textContent = "Total Bookings: " + stats.total;
                            document.getElementById('active').textContent = "Active Bookings: " + stats.active;
                            document.getElementById('failed').textContent = "Payment Failed Bookings: " + stats.failed;
                            document.getElementById('cancelled').textContent = "Cancelled Bookings: " + stats.cancelled;
                            document.getElementById('income').textContent = "Income: " + stats.income + " VND";
                        } else {
                            document.getElementById('bookings-data').innerHTML = "No data for this selected date";
                        }
                    }
                };

                xhr.send('from_date=' + encodeURIComponent(fromDate) + '&to_date=' + encodeURIComponent(toDate));
            });

            document.getElementById('exportForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const fromDate = document.getElementById('from_date').value;
                const toDate = document.getElementById('to_date').value;

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'ajax/export_excel_date.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.responseType = 'blob';

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(xhr.response);
                        link.download = "Booking_order_" + new Date().toISOString().slice(0, 19).replace(/:/g, "") + ".xls";
                        link.click();
                    }
                };

                xhr.send('export_excel=1&from_date=' + encodeURIComponent(fromDate) + '&to_date=' + encodeURIComponent(toDate));
            });
        </script>

</body>

</html>