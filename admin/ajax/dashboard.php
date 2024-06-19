<?php

require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');

if (isset($_POST['booking_analytic'])) {
    $frm_data = filteration($_POST);

    $condition = "";
    if ($frm_data['period'] == 1) {
        $condition = "WHERE date_time BETWEEN NOW() - INTERVAL 7 DAY AND NOW()";
    } else if ($frm_data['period'] == 2) {
        $condition = "WHERE date_time BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    } else if ($frm_data['period'] == 3) {
        $condition = "WHERE date_time BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }

    $query = "
    SELECT
    COUNT(booking_order.booking_id) AS `total_bookings`,
    SUM(booking_detail.total_pay) AS `total_amt`,

    COUNT(CASE WHEN `booking_order`.`booking_status` = 'booked' AND `booking_order`.arrival = 1 THEN 1 END) AS `active_bookings`,
    SUM(CASE WHEN `booking_order`.`booking_status` = 'booked' AND `booking_order`.arrival = 1 THEN `booking_detail`.total_pay END) AS `active_amt`,

    COUNT(CASE WHEN `booking_order`.`booking_status` = 'payment_failed' THEN 1 END) AS `payment_failed_bookings`,
    SUM(CASE WHEN `booking_order`.`booking_status` = 'payment_failed' THEN `booking_detail`.total_pay END) AS `payment_failed_amt`,

    COUNT(CASE WHEN `booking_order`.`booking_status` = 'cancelled'  THEN 1 END) AS `cancelled_bookings`,
    SUM(CASE WHEN `booking_order`.`booking_status` = 'cancelled' THEN `booking_detail`.total_pay END) AS `cancelled_amt`
    FROM `booking_order`
    LEFT JOIN `booking_detail` ON booking_order.booking_id = booking_detail.booking_id
    $condition";

    $result = mysqli_fetch_assoc(mysqli_query($con, $query));

    echo json_encode($result);
}


