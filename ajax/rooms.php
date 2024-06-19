<?php

require('../admin/inc/essentials.php');
require('../admin/inc/db_config.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

session_start();

if (isset($_GET['fetch_rooms'])) {
    //check availability data decode
    $chk_avail = json_decode($_GET['chk_avail'], true);

    if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($chk_avail['checkin']);
        $checkout_date = new DateTime($chk_avail['checkout']);

        if ($checkin_date == $checkout_date) {
            echo "<h3 class='text-center text-danger'>Invalid Dates!</h3>";
            exit;
        } else if ($checkout_date < $checkin_date) {
            echo "<h3 class='text-center text-danger'>Invalid Dates!</h3>";
            exit;
        } else if ($checkin_date < $today_date) {
            echo "<h3 class='text-center text-danger'>Invalid Dates!</h3>";
            exit;
        }
    }

    // guests data

    $guests = json_decode($_GET['guests'], true);
    $adults = ($guests['adults'] != '') ? $guests['adults'] : 0;
    $children = ($guests['children'] != '') ? $guests['children'] : 0;
    echo $adults;
    echo $children;

    //facilities data decode
    $facility_list = json_decode($_GET['facility_list'],true);

    // Count number of rooms and output
    $count_rooms = 0;
    $output = "";

    // Fetch settings table to check if the website is shutdown or not
    $settings_q = "SELECT * FROM `settings` WHERE `id`=1";
    $settings_r = mysqli_fetch_assoc(mysqli_query($con, $settings_q));

    // Query for room cards
    $room_res = select("SELECT * FROM `rooms` WHERE `adult`>=? AND `children`>=? AND `status`=? AND `removed`=?", [$adults, $children, 1, 0], 'iiii');
    while ($room_data = mysqli_fetch_assoc($room_res)) {
        //check availability logic filter
        if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
            $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
            WHERE booking_status=?  AND room_id=? AND check_out>? AND check_in<?";

            $values = ['booked', $room_data['id'], $chk_avail['checkin'], $chk_avail['checkout']];
            $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

            if (($room_data['quantity'] - $tb_fetch['total_bookings']) == 0) {
                continue;
            }
        }

        // Get facilities of room
        $fac_count = 0;
        $fac_q = mysqli_query($con, "SELECT f.name, f.id FROM `facilities` f
         INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
         WHERE rfac.room_id = '$room_data[id]'");

        $facilities_data = "";
        while ($fac_row = mysqli_fetch_assoc($fac_q)) 
        {
            if(in_array($fac_row['id'],$facility_list['facilities'])){
                $fac_count++;
            }
            $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                 $fac_row[name]
             </span> ";
        }

        if(count($facility_list['facilities'])!=$fac_count){
            continue;
        }

        // Get features of rooms
        $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
                        INNER JOIN `room_features` rfea ON f.id = rfea.features_id
                        WHERE rfea.room_id = '$room_data[id]'");

        $features_data = "";
        while ($fea_row = mysqli_fetch_assoc($fea_q)) {
            $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                $fea_row[name]
                            </span> ";
        }


        // Get thumbnail
        $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
        $thumb_q = mysqli_query($con, "SELECT * FROM `room_images`
                        WHERE `room_id` = '$room_data[id]'
                        AND `thumb`='1'");

        if (mysqli_num_rows($thumb_q) > 0) {
            $thumb_res = mysqli_fetch_assoc($thumb_q);
            $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
        }

        $book_btn = "";
        if (!$settings_r['shutdown']) {
            $login = 0;
            if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
                $login = 1;
            }
            $book_btn = "<a onclick='checkLoginToBook($login, $room_data[id])' class='btn w-100 btn btn-sm btn-outline-dark shadow-none mb-2'>Book Now</a>";
        }

        // Print room card
        $output .= "
            <div class='card mb-4 border-0 shadow'>
                <div class='row g-0 p-3 align-items-center'>
                    <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                        <img src='$room_thumb' class='img-fluid rounded'>
                    </div>
                    <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                        <h5 class='mb-3'>$room_data[name]</h5>
                        <div class='features mb-3'>
                            <h6 class='mb-1'>Features</h6>
                            $features_data
                        </div>
                        <div class='facilities mb-3'>
                            <h6 class='mb-1'>Facilities</h6>
                            $facilities_data
                        </div>
                        <div class='guests'>
                            <h6 class='mb-1'>Guests</h6>
                            <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                $room_data[adult] Adults
                            </span>
                            <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                $room_data[children] Children
                            </span>
                        </div>
                        <div>
                            <h6 class='mb-1 mt-2'>Hotel Name</h6>
                            <a href='hotel_details.php?id=$room_data[hotel_id]' class='text-black badge rounded-pill bg-light text-dark text-wrap'>$room_data[hotel]</a>
                        </div>
                    </div>
                    <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 text-center'>
                        <h6 class='mb-4'>$room_data[price] per night</h6>
                        $book_btn
                        <a href='room_details.php?id=$room_data[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More Details</a>
                    </div>
                </div>
            </div>";
        $count_rooms++;
    }

    if ($count_rooms > 0) {
        echo $output;
    } else {
        echo "<h3 class='text-center text-danger'>No rooms available!</h3>";
    }
}
