<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');

if (isset($_POST['year'])) {
    $year = intval($_POST['year']);

    $query = "SELECT DATE_FORMAT(date_time, '%Y-%m') AS month, COUNT(*) AS booking_count 
              FROM booking_order 
              WHERE YEAR(date_time) = $year 
              AND `booking_status`='booked'
              GROUP BY month 
              ORDER BY month";
    $result = mysqli_query($con, $query);

    $dataPoints = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $dataPoints[] = array("y" => $row['booking_count'], "label" => $row['month']);
    }

    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);


}
