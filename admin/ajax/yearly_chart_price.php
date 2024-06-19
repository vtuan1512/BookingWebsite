<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');

if (isset($_POST['year'])) {

    $year = intval($_POST['year']);
    $query = "SELECT DATE_FORMAT(bo.date_time, '%Y-%m') AS month, SUM(bd.total_pay) AS total_price
    FROM booking_order bo
    INNER JOIN booking_detail bd ON bo.booking_id = bd.booking_id
    WHERE YEAR(bo.date_time) = $year 
    AND bo.`booking_status`='booked'
    GROUP BY month 
    ORDER BY month";
    $result = mysqli_query($con, $query);

    $dataPoints = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $dataPoints[] = array("y" => $row['total_price'], "label" => $row['month']);
    }
    echo json_encode($dataPoints, JSON_NUMERIC_CHECK);
}
