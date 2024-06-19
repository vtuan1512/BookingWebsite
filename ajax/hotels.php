<?php
require('../admin/inc/essentials.php');
require('../admin/inc/db_config.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

session_start();

if (isset($_GET['fetch_rooms'], $_GET['chk_avail'], $_GET['guests'], $_GET['facility_list'])) {
    $chk_avail = json_decode($_GET['chk_avail'], true);
    $guests = json_decode($_GET['guests'], true);
    $facility_list = json_decode($_GET['facility_list'], true);
    if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($chk_avail['checkin']);
        $checkout_date = new DateTime($chk_avail['checkout']);

        if ($checkin_date == $checkout_date || $checkout_date < $checkin_date || $checkin_date < $today_date) {
            echo "<h3 class='text-center text-danger'>Invalid Dates!</h3>";
            exit;
        }
    }

    $adults = isset($guests['adults']) && $guests['adults'] !== '' ? $guests['adults'] : '';
    $children = isset($guests['children']) && $guests['children'] !== '' ? $guests['children'] : '';


    $count_rooms = 0;
    $output = "";
    $processed_hotels = array();

    $settings_q = "SELECT * FROM `settings` WHERE `id`=1";
    $settings_r = mysqli_fetch_assoc(mysqli_query($con, $settings_q));

    $room_res = select("SELECT * FROM `rooms` WHERE `adult`>=? AND `children`>=? AND `status`=? AND `removed`=?", [$adults, $children, 1, 0], 'iiii');
    if ($room_res) {
        while ($room_data = mysqli_fetch_assoc($room_res)) {
            if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
                $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
                WHERE booking_status=?  AND room_id=? AND check_out>? AND check_in<?";

                $values = ['booked', $room_data['id'], $chk_avail['checkin'], $chk_avail['checkout']];
                $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

                if (($room_data['quantity'] - $tb_fetch['total_bookings']) == 0) {
                    continue;
                }
            }

            $fac_count = 0;
            $fac_q = mysqli_query($con, "SELECT f.name, f.id FROM `facilities` f
                INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
                WHERE rfac.room_id = '{$room_data['id']}'");

            $facilities_data = "";
            $facility_id = array();
            while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                if (in_array($fac_row['id'], $facility_list['facilities'])) {
                    $fac_count++;
                    $facility_id[] = $fac_row['id'];
                }

                $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                    {$fac_row['name']}
                </span> ";
            }

            if (count($facility_list['facilities']) != $fac_count) {
                continue;
            }


            // Mã PHP để lấy dữ liệu từ cơ sở dữ liệu và in ra HTML
            if (!in_array($room_data['hotel_id'], $processed_hotels)) {
                $processed_hotels[] = $room_data['hotel_id'];
                $hotel_res = select("SELECT * FROM `hotel` WHERE `id`=? AND `status`=? AND `removed`=?" , [$room_data['hotel_id'],1,0], 'iii');
                while ($hotel_data = mysqli_fetch_assoc($hotel_res)) {
                    $hotel_thumb = HOTEL_IMG_PATH;
                    $facility_id_str = implode(',', $facility_id);
                    $more_details_url = "hotel_details.php?id={$hotel_data['id']}&checkin={$chk_avail['checkin']}&checkout={$chk_avail['checkout']}&adults={$adults}&children={$children}&facilities={$facility_id_str}";

                    echo "
                        <div class='card mb-4 border-0 shadow'>
                            <div class='row g-0 p-3 align-items-center'>
                                <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                                    <img src='$hotel_thumb$hotel_data[image]' class='img-fluid rounded w-100' style='height: 200px;'>
                                </div>
                                <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                                    <h5 class='mb-3'>$hotel_data[name]</h5> 
                                    <div class='guests'>
                                        <h6 class='mb-1'>Address</h6>
                                        <span class='badge rounded-pill bg-light text-dark text-wrap mb-1'>
                                            $hotel_data[address] 
                                        </span>
                                        <h6 class='mb-1'>Contact</h6>
                                        <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                            +84$hotel_data[phone] 
                                        </span>
                                        <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                            $hotel_data[email] 
                                        </span>
                                    </div>
                                </div>
                                <div class='col-md-2 mt-lg-1 mt-md-0 mt-2 text-center'>
                                    <h5 class='mb-2'>$hotel_data[rating]<i class='bi bi-star-fill text-warning'></i></h5>
                                    <a href='$more_details_url' class='btn btn-sm w-100 btn-outline-dark shadow-none mt-2'>More Details</a>
                                    <button type='button' class='btn btn-sm w-100 btn-outline-dark shadow-none mt-2 add-wishlist-btn' data-hotel-id='$hotel_data[id]'><i class='bi bi-heart-fill'></i>Add to Wishlist</button>
                                </div>
                            </div>
                        </div>";
                    $count_rooms++;
                }
            }
        }

        if ($count_rooms > 0) {
            echo $output;
        } else {
            echo "<h3 class='text-center text-danger'>No rooms available!</h3>";
        }
    }
}

if (isset($_POST['search_hotel'])) {
    $frm_data = filteration($_POST);

    $query = "SELECT * FROM `hotel` WHERE `name` LIKE ?";
    $res = select($query, ["%" . $frm_data['name'] . "%"], 's');

    $data = "";
    $hotel_thumb = HOTEL_IMG_PATH;

    while ($row = mysqli_fetch_assoc($res)) {
        $data .= "<div class='card mb-4 border-0 shadow'>
        <div class='row g-0 p-3 align-items-center'>
            <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
            <img src='$hotel_thumb$row[image]' class='img-fluid rounded w-100' style='height: 200px;'>
            </div>
            <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                <h5 class='mb-3'>$row[name]</h5> 
                <div class='guests'>
                    <h6 class='mb-1'>
                        Address
                    </h6>
                    <span class='badge rounded-pill bg-light text-dark text-wrap mb-1'>
                        $row[address] 
                    </span>
                    <h6 class='mb-1'>
                        Contact
                    </h6>
                    <span class='badge rounded-pill bg-light text-dark text-wrap'>
                        +84$row[phone] 
                    </span>
                    <span class='badge rounded-pill bg-light text-dark text-wrap'>
                        $row[email] 
                    </span>

                </div>
            </div>
            
            <div class='col-md-2 mt-lg-1 mt-md-0 mt-2 text-center'>
            <h5 class='mb-2'>$row[rating]<i class='bi bi-star-fill text-warning'></i></h5>
                <a href='hotel_details.php?id=$row[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none mt-2'>More Details</a>

            </div>
        </div>
    </div>";
    }
    echo $data;
}
