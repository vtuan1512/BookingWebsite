<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Confirm Booking</title>

</head>
<style>
    img.course_qr_img {
        width: 477px;
    }
</style>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <?php
    /*
        Check room id from url is present or not 
        Shutdown mode is action or not
        User is logged in or not
    */

    if (!isset($_GET['id']) || $settings_r['shutdown'] == true) {
        redirect('rooms.php');
    } else if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('rooms.php');
    }
    // Filter and get room and user data
    $data = filteration($_GET);
    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($room_res) == 0) {
        redirect('rooms.php');
    }
    $room_data = mysqli_fetch_assoc($room_res);

    $_SESSION['room'] = [
        "id" => $room_data['id'],
        "name" => $room_data['name'],
        "hotel" => $room_data['hotel'],
        "hotel_id"=>$room_data['hotel_id'],
        "price" => $room_data['price'],
        "payment" => null,
        "available" => false,
    ];
    $user_res = select(
        "SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1",
        [$_SESSION['uId']],
        "i"
    );
    $user_data = mysqli_fetch_assoc($user_res);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">CONFIRM BOOKING</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">CONFIRM</a>
                </div>
            </div>
            <div class="col-lg-7 col-md-12 px-4">
                <?php
                $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
                $thumb_q = mysqli_query($con, "SELECT * FROM `room_images`
                         WHERE `room_id` = '$room_data[id]'
                         AND `thumb`='1'");

                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
                }

                echo <<<data
                    <div class="card p-3 shadow-sm rounded">
                        <img src="$room_thumb" class="img-fluid rounded mb-3">
                        <h5>$room_data[name]-$room_data[hotel]</h5>
                        <h6>$room_data[price] per night</h6>
                        
                    </div>
                data;
                ?>
            </div>
            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form action="pay_now.php" method="POST" id="booking_form">
                            <div class="row">
                                <h6 class="col-md-6 mb-3">BOOKING DETAILS</h6>
                                <input class="col-md-6 mb-3 text-md-end" type="text" style="border: none;" name="order_id" value="<?php echo 'ORD' . $_SESSION['uId']  . random_int(11111, 999999); ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control shadow-none" row="1" required><?php echo $user_data['address'] ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label ">Check-in</label>
                                    <input name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Check-out</label>
                                    <input name="checkout" onchange="check_availability()" type="date" class="form-control shadow-none" required>
                                </div>
                                <!-- Trong phần HTML của bạn -->
                                <label class="form-label">Voucher Code</label>
                                <div class="col-md-12 mb-3 d-flex">
                                    <input id="voucher_code" name="voucher_code" class="form-control shadow-none">
                                    <input id="check_voucher" type="button" onclick="check_voucher_code()" class="btn btn-dark" value="Check voucher"></input>
                                </div>
                                <div class="col-md-12 mb-3" >
                                    <label class="form-label">Select a payment</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="pay_hotel" name="method[]" value="Hotel">
                                        <label class="form-check-label" for="pay_hotel">
                                            Pay at the hotel
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="online_payment" name="method[]" value="Online">
                                        <label class="form-check-label" for="Online_payment">
                                            Online payment
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="spinner-border text-infor mb-3 d-none" id="info_loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="col-12" id="additionalContent"></div>
                                <div class="col-12">
                                    <h6 class="mb-3 text-danger" id="pay_info">Provide check-in and check-out date !</h6>
                                    <h6 class="mb-3 text-danger" id="pay_price"></h6>
                                    <button id="Pay_now" name="pay_now" class="btn w-100 text-white bg-dark shadow-none mb-1">Book Now</button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <?php
    require('inc/footer.php');
    ?>

    <script>
        let booking_form = document.getElementById('booking_form');
        let info_loader = document.getElementById('info_loader');
        let pay_info = document.getElementById('pay_info');
        let pay_price = document.getElementById('pay_price');

        function check_availability() {
            let checkin_val = booking_form.querySelector('input[name="checkin"]').value;
            let checkout_val = booking_form.querySelector('input[name="checkout"]').value;

            booking_form.querySelector('button[name="pay_now"]').setAttribute('disabled', true);

            if (checkin_val !== '' && checkout_val !== '') {

                pay_info.classList.add('d-none');
                pay_info.classList.replace('text-dark', 'text-danger');
                pay_price.classList.add('d-none');
                pay_price.classList.replace('text-dark', 'text-danger');
                info_loader.classList.remove('d-none');

                let data = new FormData();

                data.append('check_availability', '');
                data.append('check_in', checkin_val);
                data.append('check_out', checkout_val);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/confirm_booking.php", true);
                xhr.onload = function() {
                    let data = JSON.parse(this.responseText);
                    if (data.status == 'check_in_out_equal') {
                        pay_info.innerText = "You cannot check-out on the same day!";
                    } else if (data.status == 'check_out_earlier') {
                        pay_info.innerText = "Check-out date must be earlier than check-in date!";
                    } else if (data.status == 'check_in_earlier') {
                        pay_info.innerText = "Check-in date must be earlier than today's date!";
                    } else if (data.status == 'unavailable') {
                        pay_info.innerText = "Room not available for this check-in date!";
                    } else {
                        pay_info.innerHTML = "<br>Name: " + "<?php echo $user_data['name'] ?>" + "<br>Phone Number :" + "<?php echo $user_data['phonenum'] ?>" +
                            "<br>No. of days: " + data.days;
                        pay_price.innerHTML =  "<br>Total Amount to Pay: " + data.payment + "VND";
                        pay_price.classList.replace('text-danger', 'text-dark')
                        pay_info.classList.replace('text-danger', 'text-dark');
                        booking_form.querySelector('button[name="pay_now"]').removeAttribute('disabled');
                        paidPrice = data.payment;
                    }
                    pay_info.classList.remove('d-none');
                    pay_price.classList.remove('d-none');
                    info_loader.classList.add('d-none');

                };

                xhr.send(data);
            }
        }
        let voucherEntered = false;

        function check_voucher_code() {
            let voucher_code = document.querySelector('input[name="voucher_code"]').value;
            let checkin_val = document.querySelector('input[name="checkin"]').value;
            let checkout_val = document.querySelector('input[name="checkout"]').value;
            pay_info.classList.remove('d-none');
            pay_price.classList.remove('d-none');
            // booking_form.querySelector('button[name="pay_now"]').setAttribute('disabled', true);

            if (voucher_code !== '') {
                let data1 = new FormData();
                data1.append('check_in', checkin_val);
                data1.append('check_out', checkout_val);
                data1.append('voucher_code', voucher_code);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/confirm_booking.php", true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        console.log(xhr.responseText);
                        let responseData = xhr.responseText.split('}');
                        console.log(responseData);
                        let data1 = JSON.parse(responseData[0] + '}');
                        console.log(data1);
                        if (data1.status === 'matched') {
                            pay_info.innerHTML = "Name: " + "<?php echo $user_data['name'] ?>" + "<br>Phone Number :" + "<?php echo $user_data['phonenum'] ?>" + "<br>No. of days: " + data1.days ;
                            pay_price.innerHTML =  "New total amount to Pay: " + data1.new_payment + "VND";
                            pay_info.classList.replace('text-danger', 'text-dark');
                            pay_price.classList.replace('text-danger', 'text-dark');
                            booking_form.querySelector('button[name="pay_now"]').removeAttribute('disabled');
                            paidPrice = data1.new_payment;

                        } else if (data1.status === 'voucher_quantity_zero') {
                            alert('error', 'Số lượng voucher đã hết!');
                        } else if (data1.status === 'invalid_hotel') {
                            alert('error', 'Voucher khong duoc dung trong khach san nay!');
                        }else if (data1.status === 'booking_value_below_min') {
                            alert('error', 'Giá trị đặt phòng của bạn không đủ để sử dụng voucher này!');
                        } else if (data1.status === 'not_matched') {
                            alert('error', 'Voucher code không hợp lệ!');
                        } else if (data1.status === 'voucher_already_used') {
                            alert('error', 'Bạn đã sử dụng voucher code!');
                        }
                        pay_price.classList.remove('d-none');
                        pay_info.classList.remove('d-none');
                        info_loader.classList.add('d-none');
                    } else {
                        alert('error', 'Đã có lỗi xảy ra từ máy chủ. Vui lòng thử lại sau.');
                    }
                };
                xhr.send(data1);
            } else {
                alert('error', 'Vui lòng nhập voucher code.');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var checkbox1 = document.getElementById('pay_hotel');
            var checkbox2 = document.getElementById('online_payment');

            function onlyOneCheckboxChecked(checkedCheckbox) {
                if (checkedCheckbox === checkbox1) {
                    checkbox2.checked = false;
                } else if (checkedCheckbox === checkbox2) {
                    checkbox1.checked = false;
                }
            }
            checkbox1.addEventListener('click', function() {
                onlyOneCheckboxChecked(checkbox1);
            });

            checkbox2.addEventListener('click', function() {
                onlyOneCheckboxChecked(checkbox2);
            });

        });



        document.getElementById('pay_hotel').addEventListener('change', function() {
            if (this.checked) {
                var onlinePaymentDiv = document.querySelector('.form-group');
                if (onlinePaymentDiv) {
                    onlinePaymentDiv.remove();
                }
            }
        });

    </script>


</body>

</html>