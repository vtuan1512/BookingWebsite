<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
ob_start();
adminLogin();
ob_clean();

$response = array(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $hotelId = $_SESSION['hotelId'];  

    try {
        $query = "SELECT * FROM booking_order WHERE date_time BETWEEN ? AND ? AND hotel_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('ssi', $from_date, $to_date, $hotelId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['bookings'] = array();
            while ($row = $result->fetch_assoc()) {
                $response['bookings'][] = $row;
            }
        } else {
            $response['message'] = "No bookings found for the selected date range.";
        }
        $statQuery = "
            SELECT
                COUNT(booking_id) AS total,
                SUM(CASE WHEN booking_status = 'booked' THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN booking_status = 'payment_failed' THEN 1 ELSE 0 END) AS failed,
                SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) AS cancelled,
                SUM(CASE WHEN booking_status = 'booked' THEN (
                    SELECT total_pay FROM booking_detail WHERE booking_detail.booking_id = booking_order.booking_id
                ) ELSE 0 END) AS income
            FROM 
                booking_order
            WHERE 
                date_time BETWEEN ? AND ? AND hotel_id = ?";

        $statStmt = $con->prepare($statQuery);
        $statStmt->bind_param('ssi', $from_date, $to_date, $hotelId);
        $statStmt->execute();
        $statResult = $statStmt->get_result()->fetch_assoc();

        $response['statistics'] = $statResult;

        echo json_encode($response);

        $stmt->close();
        $statStmt->close();
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
        echo json_encode($response);
    }
}

$con->close();
