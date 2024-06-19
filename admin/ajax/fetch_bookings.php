<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
adminLogin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Query to fetch bookings within the date range
    $query = "SELECT * FROM booking_order WHERE  date_time BETWEEN ? AND ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('ss', $from_date, $to_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookingsData = array();

    if ($result->num_rows > 0) {
        $bookingsData['bookings'] = array();
        $i = 1;
        while ($row = $result->fetch_assoc()) {
            $bookingsData['bookings'][] = $row;
            $i++;
        }
    } else {
        $bookingsData['message'] = "No bookings found for the selected date range.";
    }

    // Fetch statistical data
    $statQuery = "
        SELECT
            COUNT(booking_id) AS total,
            SUM(CASE WHEN booking_status = 'booked' THEN 1 END) AS active,
            SUM(CASE WHEN booking_status = 'payment_failed' THEN 1  END) AS failed,
            SUM(CASE WHEN booking_status = 'cancelled' THEN 1  END) AS cancelled,
            SUM(CASE WHEN booking_status = 'booked' THEN (
                SELECT total_pay FROM booking_detail WHERE booking_detail.booking_id = booking_order.booking_id
            )  END) AS income
        FROM 
            booking_order
        WHERE 
        date_time BETWEEN ? AND ?";

    $statStmt = $con->prepare($statQuery);
    $statStmt->bind_param('ss', $from_date, $to_date);
    $statStmt->execute();
    $statResult = $statStmt->get_result()->fetch_assoc();

    $bookingsData['statistics'] = $statResult;

    echo json_encode($bookingsData);
}


?>


