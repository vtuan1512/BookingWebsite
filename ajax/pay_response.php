<?php
require('../admin/inc/essentials.php');
require('../admin/inc/db_config.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

session_start();
unset($_SESSION['room']);
function regenrate_session($uid)
{
    $user_q = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",[$uid],'i');
    $user_fetch = $user_q->fetch_assoc($user_q);
    $_SESSION['login'] = true;
    $_SESSION['uId'] = $user_fetch['id'];
    $_SESSION['uName'] =  $user_fetch['name'];
    $_SESSION['uPic'] =  $user_fetch['profile'];
    $_SESSION['uPhone'] =  $user_fetch['phonenum'];
}

$response = []; 

if (isset($_POST['update'])) {
    $frm_data = filteration($_POST);
    $paid_content = mysqli_real_escape_string($con, $_POST['paid_content']);
    $paid_price = mysqli_real_escape_string($con, $_POST['paid_price']);

    $slct_query = "SELECT `booking_id`, `user_id` FROM `booking_order` 
    WHERE `order_id`= '$paid_content'";

    $slct_res = mysqli_query($con, $slct_query);
    if (mysqli_num_rows($slct_res) == 0) {
        redirect('index.php');
    }
    $slct_fetch = mysqli_fetch_assoc($slct_res);

    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        regenrate_session($slct_fetch['user_id']);
    }
    $status = $_POST['status'];
    if ($status == 'success') {
        $upd_query = "UPDATE `booking_order` 
        SET `trans_atm` = '$paid_price', 
            `trans_status` = 'success', 
            `booking_status` = 'booked'
        WHERE `booking_id` = '{$slct_fetch['booking_id']}'";
        mysqli_query($con, $upd_query);
        $response['status'] = 'success';
        $response['paid_content'] = $paid_content;
    } else {
        $upd_query = "UPDATE `booking_order` 
            SET `trans_atm` = '$paid_price', 
                `trans_status` = 'failed', 
                `booking_status` = 'payment_failed'
            WHERE `order_id` = '$paid_content'";
        mysqli_query($con, $upd_query);
        $response['status'] = 'failed';
        
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
