<?php

require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
// adminLogin();
session_start();

if (isset($_POST['get_bookings'])) 
{
    $frm_data = filteration($_POST);
    $manager_hotel_id = $_SESSION['hotelId'] ?? null;

    if (!$manager_hotel_id) {
        echo "<b>No data found!</b> - Manager hotel ID not set";
        exit;
    }

    $query = "SELECT bo.*, bd.* FROM `booking_order` bo 
    INNER JOIN `booking_detail` bd ON bo.booking_id=bd.booking_id
    WHERE bo.hotel_id = ? AND (
        bo.order_id LIKE ? OR bd.phone_num LIKE ? OR bd.user_name LIKE ?
    ) AND (bo.booking_status= ? AND bo.refund = ?)
    ORDER BY bo.booking_id ASC";

    $res = select($query,[$manager_hotel_id, "%$frm_data[search]%", "%$frm_data[search]%", "%$frm_data[search]%", "cancelled", 0], 'sssssi');

    $i = 1;
    $table_data = "";
    if(mysqli_num_rows($res)==0)
    {
        echo "<b>No data found!</b>";
        exit;
    }
    while ($data = mysqli_fetch_assoc($res)) {
        $date = date("d-m-Y", strtotime($data['date_time']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        $table_data .= "
        <tr>
            <td> $i</td>
            <td>
                <span class='badge bg-primary'>
                    Order ID : $data[order_id]
                </span>
                <br>
                <b>Name :</b> $data[user_name]
                <br>
                <b>Phone No :</b> $data[phone_num]
            </td>
            <td>
                <b>Room :</b> $data[room_name]
                <br>
                <b>Hotel Name :</b> $data[hotel_name]
                <br>
                <b>Payment method :</b> $data[payment_method]
                <br>
                <b>Check in:</b> $checkin
                <br>
                <b>Check out:</b> $checkout
                <br>
                <b>Online Paid :</b> $data[trans_atm] VND
                <br>
                <b>Date :</b> $date
            </td>
            <td>
                <b>$data[trans_atm]VND</b> 
            </td>
            <td>
                <button type='button' onclick='refund_booking($data[booking_id])' class='btn btn-success mt-2 btn-sm fw-bold shadow-none'>
                    <i class='bi bi-cash-stack'></i> Refund Booking
                </button>
            </td>
        </tr>
    ";
    $i++;
    }
    echo $table_data;
}

if (isset($_POST['assign_room'])) 
{
    $frm_data = filteration($_POST);
    $query ="UPDATE `booking_order` bo INNER JOIN `booking_detail` bd
    ON bo.booking_id = bd.booking_id
    SET bo.arrival = ?, bd.room_no=?
    WHERE bo.booking_id = ?";

    $values =[1,$frm_data['room_no'],$frm_data['booking_id']];

    $res = update($query,$values,'isi'); // it will update 2 rows so it will return 2

    echo($res==2)?1:0;
}


if (isset($_POST['refund_booking'])) {
    $frm_data =  filteration($_POST);

    $query ="UPDATE `booking_order` SET `refund` =? WHERE `booking_id` = ?";

    $values =[1,$frm_data['booking_id']]; // set refund = 1

    $res = update($query,$values,'ii');

    echo $res;
}
