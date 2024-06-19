<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

if (is_array($_POST)) {
    foreach ($_POST as $key => $value) {
        $_POST[$key] = filteration($value);
    }
}

if (isset($_POST['pay_now'])) {
    $methods = isset($_POST['method']) ? $_POST['method'] : array();
    $method = implode(', ', $methods);

    $ORDER_ID = $_POST['order_id'];
    $query1 = "INSERT INTO `booking_order`(`user_id`, `room_id`,`hotel_id` ,`check_in`, `check_out`, `order_id`) VALUES (?,?,?,?,?,?)";

    insert($query1, [
        $_SESSION['uId'], $_SESSION['room']['id'], $_SESSION['room']['hotel_id'], $_POST['checkin'], $_POST['checkout'], $ORDER_ID
    ], 'isisss');


    $booking_id = mysqli_insert_id($con);
    $total_pay = isset($_SESSION['room']['new_payment']) ? $_SESSION['room']['new_payment'] : $_SESSION['room']['payment'];

    $query2 = "INSERT INTO `booking_detail`(`booking_id`, `room_name`,`hotel_name`,`price`, `total_pay`, `voucher_code`, `payment_method` ,`user_name`, `phone_num`, `address`) VALUES (?,?,?,?,?,?,?,?,?,?)";

    insert($query2, [
        $booking_id, $_SESSION['room']['name'], $_SESSION['room']['hotel'], $_SESSION['room']['price'], $total_pay,
        $_POST['voucher_code'], $method, $_POST['name'], $_POST['phonenum'], $_POST['address']
    ], 'isssssssss');

    // Fetch bank information based on hotel_id
    $hotel_id = $_SESSION['room']['hotel_id'];
    $bank_query = "SELECT `bank`, `bank_account` FROM `hotel` WHERE `id` = ?";
    $bank_result = select($bank_query, [$hotel_id], 'i');
    
    if ($bank_result && $bank_result->num_rows > 0) {
        $bank_info = $bank_result->fetch_assoc();
        $bank_id = $bank_info['bank'];
        $account_no = $bank_info['bank_account'];
    } else {
        $bank_id = "DEFAULT_BANK_ID";
        $account_no = "DEFAULT_ACCOUNT_NO";
    }

    if (strpos($method, 'Online') !== false) {
?>
        <style>
            .payment-container {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin-right: 10px;
            }

            .card {
                display: flex;
                width: 60rem;
                text-align: left;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .course_qr_img {
                width: 50%;
                object-fit: cover;
            }

            .card-body {
                padding: 20px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: flex-start;
                width: 50%;
            }

            .card-body p {
                margin: 10px 0;
            }

            #confirmation_button {
                width: 10rem;
                height: 50px;
                background-color: black;
                color: white;
                border-radius: 10px;
                cursor: pointer;
                text-align: center;
                border: none;
                margin-top: 69px;
                margin-left: 104px;
            }

            #countdown {
                text-align: center;
                font-weight: bold;
                color: red;
                margin-left: 111px;
                font-size: 20px;
                margin-top: 29px;
            }
        </style>

        <form id="payment_form" method="POST">
            <div class="payment-container">
                <div class="card">
                    <img class="course_qr_img" src="" alt="QR Code" class="card-img-top">
                    <div class="card-body">
                        <div id="countdown"></div>
                        <h1>Booking Hotel Da Lat</h1>
                        <p class="card-text">Please do not reload the page. You have 10 minutes to pay for this booking. After payment, click the confirmation button
                            to complete the booking. If you have not paid after 10 minutes, your application will be canceled.<br>
                            Thank you for your reservation! Below is the payment information</p>
                        <h4>Transfer content: <span id="paid_content"><?php echo $ORDER_ID; ?></span></h4>
                        <h4>Price: <span id="paid_price"><?php echo $total_pay; ?></span></h4>
                        <input id="confirmation_button" type="button" value="Payment confirmation">
                    </div>

                </div>
            </div>
            <div id="countdown"></div>
        </form>
        <script>
            let MY_BANK = {
            BANK_ID: "<?php echo $bank_id; ?>",
            ACCOUNT_NO: "<?php echo $account_no; ?>"
        }
        </script>
        <script src="./admin/scripts/QR.js"></script>
<?php

    } else {
        $slct_query = "SELECT `booking_id`, `user_id` FROM `booking_order` 
            WHERE `order_id`= '$ORDER_ID'";

        $slct_res = mysqli_query($con, $slct_query);
        if (mysqli_num_rows($slct_res) == 0) {
            redirect('index.php');
        }
        $slct_fetch = mysqli_fetch_assoc($slct_res);

        $upd_query = "UPDATE `booking_order` 
        SET `trans_status` = 'success', 
            `booking_status` = 'booked'
        WHERE `booking_id` = '{$slct_fetch['booking_id']}'";
        mysqli_query($con, $upd_query);
        redirect('pay_status.php?order=' . $ORDER_ID);
    }
}

?>