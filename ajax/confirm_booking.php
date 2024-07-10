<?php
require('../admin/inc/essentials.php');
require('../admin/inc/db_config.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

session_start();

if (isset($_POST['check_availability'])) {
    $frm_data = filteration($_POST);
    $status = "";
    $result = "";

    $today_date = new DateTime(date("Y-m-d"));
    $checkin_date = new DateTime($frm_data['check_in']);
    $checkout_date = new DateTime($frm_data['check_out']);

    if ($checkin_date == $checkout_date) {
        $status = 'check_in_out_equal';
        $result = json_encode(["status" => $status]);
    } else if ($checkout_date < $checkin_date) {
        $status = 'check_out_earlier';
        $result = json_encode(["status" => $status]);
    } else if ($checkin_date < $today_date) {
        $status = 'check_in_earlier';
        $result = json_encode(["status" => $status]);
    }

    if ($status != '') {
        echo $result;
    } else {
        if (!isset($_SESSION['room']['price'])) {
            $status = 'price_not_set';
            $result = json_encode(["status" => $status]);
            echo $result;
            exit;
        }

        // check room available or not
        // tao total_bookings nhu bien dem de kiem tra xem co bnhieu phong trung voi thoi gian dat
        // neu thoa man ca 2 dieu kien kia => bien tang them 1
        // neu thoa man 1 trong 2 thi k dc tinh
        // sau khi tang len so so luong phong tu cot quantity - cai bien total kia.
        // neu bang 0 thi ko thoa man hoac neu 
        $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
        WHERE booking_status=?  AND room_id=? AND check_out>? AND check_in<?";

        $values = ['booked', $_SESSION['room']['id'], $frm_data['check_in'], $frm_data['check_out']];
        $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

        $rq_result = select("SELECT `quantity` FROM `rooms` WHERE `id`=?", [$_SESSION['room']['id']], 'i');
        $rq_fetch = mysqli_fetch_assoc($rq_result);

        if (($rq_fetch['quantity'] - $tb_fetch['total_bookings']) == 0) {
            $status = "unavailable";
            $result = json_encode(["status" => $status]);
            echo $result;
            exit;
        }
        // calculate payment
        $count_days = date_diff($checkin_date, $checkout_date)->days;
        $payment = $_SESSION['room']['price'] * $count_days;
        $_SESSION['room']['payment'] = $payment;
        $_SESSION['room']['available'] = true;
        $result = json_encode(["status" => 'available', "days" => $count_days, "payment" => $payment]);
        echo $result;
    }
}

if (isset($_POST['voucher_code'])) {
    $frm_data = filteration($_POST);
    $voucher_code = $frm_data['voucher_code'];
    global $con;
    $checkin_date = new DateTime($frm_data['check_in']);
    $checkout_date = new DateTime($frm_data['check_out']);

    // Get hotel_id from the session room data
    $room_id = $_SESSION['room']['id'];
    $sql_get_hotel_id = "SELECT hotel_id FROM `rooms` WHERE `id` = ?";
    $result_hotel_id = select($sql_get_hotel_id, array($room_id), 'i');
    $hotel_id = $result_hotel_id->fetch_assoc()['hotel_id'];

    // Check voucher details and hotel_id
    $sql_check = "SELECT * FROM `voucher` WHERE `voucher_code` = ?";
    $result_check = select($sql_check, array($voucher_code), 's');

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();

        // Check if the voucher is for the same hotel or has a null hotel_id
        if ($row['hotel_id'] === 0 || $row['hotel_id'] == $hotel_id) {
            $voucher_type = $row['voucher_type'];
            $booking_min_value = $row['booking_min_value'];
            $quantity = $row['quantity'];
            $status = 'matched';
            if ($_SESSION['room']['payment'] >= $booking_min_value) {
                if ($quantity > 0) {
                    if ($voucher_type === "percent") {
                        $voucher_value = $row['voucher_value'];
                        if (!isset($_SESSION['room']['price'])) {
                            $status = 'price_not_set';
                            $result = json_encode(["status" => $status]);
                            echo $result;
                            exit;
                        }
                        $count_days = date_diff($checkin_date, $checkout_date)->days;
                        $payment = $_SESSION['room']['price'] * $count_days;
                        $_SESSION['room']['payment'] = $payment;
                        $_SESSION['room']['available'] = true;
                        $discount = ($payment * $voucher_value) / 100;
                        $new_payment = $payment - $discount;
                        $_SESSION['room']['new_payment'] = $new_payment;
                        $result = json_encode(["status" => 'matched', "days" => $count_days, "new_payment" => $new_payment]);
                        echo $result;
                    } else {
                        $voucher_value = $row['voucher_value'];
                        if (!isset($_SESSION['room']['price'])) {
                            $status = 'price_not_set';
                            $result = json_encode(["status" => $status]);
                            echo $result;
                            exit;
                        }
                        $count_days = date_diff($checkin_date, $checkout_date)->days;
                        $payment = $_SESSION['room']['price'] * $count_days;
                        $_SESSION['room']['payment'] = $payment;
                        $_SESSION['room']['available'] = true;
                        $new_payment = $payment - $voucher_value;
                        $_SESSION['room']['new_payment'] = $new_payment;
                        $result = json_encode(["status" => 'matched', "days" => $count_days, "new_payment" => $new_payment]);
                        echo $result;
                    }
                    echo json_encode(["status" => $status, "voucher_type" => $voucher_type, "voucher_value" => $voucher_value, "booking_min_value" => $booking_min_value, "quantity" => $quantity]);
                } else {
                    $status = 'voucher_quantity_zero';
                    echo json_encode(["status" => $status]);
                }
            } else {
                $status = 'booking_value_below_min';
                echo json_encode(["status" => $status]);
            }
        } else {
            $status = 'invalid_hotel';
            echo json_encode(["status" => $status]);
        }
    } else {
        $status = 'not_matched';
        echo json_encode(["status" => $status]);
    }
    exit;
}
