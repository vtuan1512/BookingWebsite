<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Bookings</title>

</head>
<style>
    img.course_qr_img {
        width: 477px;
    }

    .custom-alert {
        position: fixed;
        top: 80px;
        right: 25px;
        z-index: 11111;

    }
</style>

<body class="bg-light">
    <?php require('inc/header.php');
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">MY BOOKINGS</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">BOOKINGS</a>
                </div>
            </div>

            <?php

            $query = "SELECT bo.*, bd.* FROM `booking_order` bo 
            INNER JOIN `booking_detail` bd ON bo.booking_id=bd.booking_id
            WHERE ((bo.booking_status='booked')
            OR (bo.booking_status='cancelled') 
            OR (bo.booking_status='payment_failed')) AND
            (bo.user_id=?)
            ORDER BY bo.booking_id DESC";
            $result = select($query, [$_SESSION['uId']], 'i');

            if (mysqli_num_rows($result) == 0) {
                echo "<h3 class='text-center text-danger'>You don't have any reservations yet !</h3>";
            } else {
                while ($data = mysqli_fetch_assoc($result)) {
                    $date = date("d-m-Y", strtotime($data['date_time']));
                    $checkin = date("d-m-Y", strtotime($data['check_in']));
                    $checkout = date("d-m-Y", strtotime($data['check_out']));

                    $status_bg = "";
                    $btn = "";
                    if ($data['booking_status'] == 'booked') {
                        $status_bg = 'bg-success';
                        if ($data['arrival'] == 1) {
                            $btn = "<a class='btn btn-dark btn-sm shadow-none' href='confirm_booking.php?id=$data[room_id]'> Rebooking </a>";
                            if ($data['rate_review'] == 0) {
                                $btn .= "<button type='button' onclick='review_room($data[booking_id],$data[room_id])' data-bs-toggle='modal' data-bs-target='#reviewModal'
                     class='btn btn-dark btn-sm shadow-none ms-2'>Rate & Review</button>";
                            }
                        } else {
                            $btn = "<button onclick='cancel_booking($data[booking_id])' type='button' class='btn btn-danger btn-sm shadow-none'>Cancel</button>";
                        }
                    } else if ($data['booking_status'] == 'cancelled') {
                        $status_bg = 'bg-danger';
                        if ($data['refund'] == 0) {
                            $btn = "<span class='badge bg-primary'> Refund in process! </span>";
                        } else {
                            $btn = "<a class='btn btn-dark btn-sm shadow-none' href='confirm_booking.php?id=$data[room_id]'> Rebooking </a>";
                        }
                    } else {
                        $status_bg = 'bg-warning text-dark';
                        $btn = "<a class='btn btn-dark btn-sm shadow-none' href='confirm_booking.php?id=$data[room_id]'> Rebooking </a>";
                    }

                    echo <<<bookings
                            <div class='col-md-4 px-4 mb-4'>
                                <div class='bg-white p-3 rounded shadow-sm'>
                                    <h5 class='fw-bold'>$data[room_name]</h5>
                                    <p>$data[price]VND per night </p>
                                    <p>
                                        <b>Hotel: </b> $data[hotel_name] <br>
                                        <b>Check in: </b> $checkin <br>
                                        <b>Check out: </b> $checkout 
                                    </p>
                                    <p>
                                        <b>Amount: </b> $$data[price] <br>
                                        <b>Order ID: </b> $$data[order_id]<br>
                                        <b>Date: </b> $$date
                                    </p>
                                    <p>
                                        <span class='badge $status_bg'>$data[booking_status]</span>
                                    </p>
                                    $btn
                                </div>
                            </div>
                            bookings;
                }
            }
            ?>


        </div>
    </div>

    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-text"></i> Rate and Review
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Email / Mobile</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Perfer</option>
                                <option value="4">Good</option>
                                <option value="3">Ok</option>
                                <option value="2">Poor</option>
                                <option value="1">Bad</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Review</label>
                            <textarea type="text" name="review" rows="3" required class="form-control shadow-none"></textarea>
                        </div>
                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_id">
                        <div class="text-end">
                            <button type="submit" class="btn btn-dark btn-sm shadow-none ">SUBMIT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
    if (isset($_GET['cancel_status'])) {
        alert('success', 'Booking Cancelled!');
    } else if (isset($_GET['review_status'])) {
        alert('success', 'Thanks for rating & review!');
    }

    ?>

    <?php
    require('inc/footer.php');
    ?>


    <script>
        function cancel_booking(id) {
            if (confirm('Are you sure you want to cancel this booking?')) {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', 'ajax/cancel_bookings.php', true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onload = function() {
                    if (this.responseText == 1) {
                        window.location.href = "bookings.php?cancel_status=true";
                        alert('success', 'Booking Cancelled!');
                    } else {
                        alert('error', 'Cancel Booking failed!');
                    }
                }

                xhr.send('cancel_booking&id=' + id);
            }
        }

        let review_form = document.getElementById('review-form');

        function review_room(bid, rid) {
            review_form.elements['booking_id'].value = bid;
            review_form.elements['room_id'].value = rid;
        }

        review_form.addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData();
            formData.append('review_form', '');
            formData.append('rating', review_form.elements['rating'].value);
            formData.append('review', review_form.elements['review'].value);
            formData.append('booking_id', review_form.elements['booking_id'].value);
            formData.append('room_id', review_form.elements['room_id'].value);

            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/review_room.php", true)
            xhr.onload = function() {
                if (this.responseText == 1) {
                    window.location.href = 'bookings.php?review_status=true'
                } else {
                    var myModal = document.getElementById('reviewModal');
                    var modal = bootstrap.Modal.getInstance(myModal);
                    modal.hide();
                    alert('error', 'Rating and Review failed!');
                }
            }
            xhr.send(formData);

        });
    </script>


</body>

</html>