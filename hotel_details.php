<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']; ?> - Hotel Details</title>
</head>
<style>
    .room-images {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        gap: 10px;
    }

    .image-container {
        flex: 0 0 auto;
    }

    .room-img {
        max-height: 100px;
        width: auto;
        display: block;
        border-radius: 5px;
    }
</style>


<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <?php
    if (!isset($_GET['id'])) {
        redirect('hotels.php');
    }

    $data = filteration($_GET);
    $hotel_res = select("SELECT * FROM `hotel` WHERE `id`=? ", [$data['id']], 'i');

    if (mysqli_num_rows($hotel_res) == 0) {
        redirect('hotels.php');
    }
    $hotel_data = mysqli_fetch_assoc($hotel_res);
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold"><?php echo $hotel_data['name']; ?></h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">ROOMS</a>
                    <span class="text-secondary"> > </span>
                    <a class="text-secondary text-decoration-none">HOTEL</a>
                </div>
                <div style="font-size: 16px; margin-top: 10px;">
                    <i class="bi bi-geo-alt-fill"></i>
                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($hotel_data['name']); ?>" target="_blank" class="text-decoration-none">
                        <?php echo $hotel_data['address']; ?>
                    </a>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6 px-4">
                    <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $hotel_img = $hotel_data['image'];
                            echo "
                                <div class='carousel-item active'>
                                    <img src='" . HOTEL_IMG_PATH . $hotel_img . "' class='d-block w-100'>
                                </div>
                            ";
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 px-4">
                <div class="mb-5">
                    <h5>Description</h5>
                    <?php echo $hotel_data['description']; ?>
                </div>

                <div class="mb-5">
                    
                    <div class="row">

                        <?php
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    $sql = "SELECT * FROM `voucher` WHERE `hotel_id` = ?";
                    $stmt = $con->prepare($sql);
                    $stmt->bind_param('i', $id);
                    $hotel_id = 0;
                    $stmt->execute();
                    
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $from_date_timestamp = strtotime($row['from_date']);
                            $to_date_timestamp = strtotime($row['to_date']);
                            $current_timestamp = time();
                            
                            if ($current_timestamp >= $from_date_timestamp && $current_timestamp <= $to_date_timestamp) {
                                echo <<<data
                                <h5 class="mb-4">Special Discount</h5>
                                <div class="col-lg-4">
                                    <div class="card mb-3" style="max-width: 540px;">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="/images/logo.png" class="img-fluid rounded-start" alt="Hotel Logo">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title text-success">Discount $row[voucher_value]  $row[voucher_type]</h5>
                                                    <p class="card-text"><small class="text-body-secondary"> $row[description]</small></p>
                                                    <p class="card-text"><small class="text-body-secondary">From $row[from_date] to $row[to_date]</small></p>
                                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                                        <div class="mb-3 bg-white voucher">
                                                            <a href="#" class="text-decoration-none text-dark copy-voucher-code">Copy code: $row[voucher_code]</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                data;
                            }
                        }
                    }
                    else 
                    {
                    }
                    ?>
                    </div>
                </div>

                <div class="mb-5">
                    <h5>Another Room</h5>
                    <hr>
                    <div class="container availability-form">
                        <div class="col-lg-12-bg-white  p-4 rounded">
                            <form id="filterform">
                                <div class="row align-items-end">
                                    <div class="col-lg-3 mb-3 " style="margin-left: 0px;">
                                        <label class="form-label" style="font-weight: 500;">Check-in</label>
                                        <input type="date" class="form-control " name="checkin_detail" value="<?php echo isset($_GET['checkin']) ? $_GET['checkin'] : ''; ?>" required>
                                    </div>
                                    <div class="col-lg-3 mb-3 ">
                                        <label class="form-label" style="font-weight: 500;">Check-out</label>
                                        <input type="date" class="form-control " name="checkout_dettail" value="<?php echo isset($_GET['checkout']) ? $_GET['checkout'] : ''; ?>" required>
                                    </div>
                                    <div class="col-lg-2 mb-3 ">
                                        <label class="form-label" style="font-weight: 500;">Adult</label>
                                        <input type="number" min="0" id="adult" name="adults_detail" value="<?php echo isset($_GET['adults']) ? $_GET['adults'] : ''; ?>" class="form-control shadow-none">
                                    </div>
                                    <div class="col-lg-1 mb-3 ">
                                        <label class="form-label" style="font-weight: 500;">Children</label>
                                        <input type="number" min="0" id="children" name="children_detail" value="<?php echo isset($_GET['children']) ? $_GET['children'] : ''; ?>" class="form-control shadow-none">
                                    </div>
                                    <div class="col-lg-2 mb-3 ">
                                        <label class="form-label" style="font-weight: 500;">Facilities</label>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary bg-white text-black dropdown-toggle" type="button" id="facilitiesDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                Select Facilities
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="facilitiesDropdown">
                                                <?php

                                                $facilities_q = selectAll('facilities');
                                                $selected_facilities = isset($_GET['facilities']) ? explode(',', $_GET['facilities']) : [];
                                                while ($row = mysqli_fetch_assoc($facilities_q)) {
                                                    $checked = in_array($row['id'], $selected_facilities) ? 'checked' : '';
                                                    echo <<<facilities
                                                        <li class="dropdown-item">
                                                            <input type="checkbox" onclick="fetch_rooms()" name="facilities[]" value="$row[id]" id="facility_$row[id]" class="form-check-input shadow-none me-1" $checked>
                                                            <label class="form-check-label" for="facility_$row[id]">$row[name]</label>
                                                        </li>
                                                    facilities;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Các trường hidden để truyền dữ liệu -->
                                    <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
                                    <input type="hidden" name="facilities" value="<?php echo $facilities; ?>">
                                    <input type="hidden" name="check_availability">
                                    <div class="col-lg-1 mb-3 mt-2">
                                        <button type="submit" class="btn  btn-outline-dark shadow-none">Submit</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>


                    <?php
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    $checkin = isset($_GET['checkin']) ? $_GET['checkin'] : '';
                    $checkout = isset($_GET['checkout']) ? $_GET['checkout'] : '';
                    $adults = isset($_GET['adults']) ? $_GET['adults'] : '';
                    $children = isset($_GET['children']) ? $_GET['children'] : '';
                    $facilities = isset($_GET['facilities']) ? $_GET['facilities'] : '';
                    $check_avail = isset($_GET['check_availability']) ? $_GET['check_availability'] : '';
                    $paramsMissing = empty($checkin) && empty($checkout) && empty($adults) && empty($children) && empty($facilities) && empty($check_avail);

                    if ($paramsMissing) {
                        $room_res = select("SELECT * FROM `rooms` WHERE `hotel_id`=? AND `status`=? AND `removed`=?", [$id, 1, 0], 'iii');
                        if (mysqli_num_rows($room_res) == 0) {
                            echo "<p>No rooms available for this hotel.</p>";
                        } else {
                            while ($room_data = mysqli_fetch_assoc($room_res)) {
                                // Get facilities of room
                                $fac_q = mysqli_query($con, "SELECT f.name, f.id FROM `facilities` f
                                    INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
                                    WHERE rfac.room_id = '{$room_data['id']}'");

                                $facilities_data = "";
                                while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                                    $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                            {$fac_row['name']}
                                        </span> ";
                                }

                                // Get features of rooms
                                $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
                                    INNER JOIN `room_features` rfea ON f.id = rfea.features_id
                                    WHERE rfea.room_id = '{$room_data['id']}'");

                                $features_data = "";
                                while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                                    $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                            {$fea_row['name']}
                                        </span> ";
                                }

                                // Get thumbnail
                                $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
                                $thumb_q = mysqli_query($con, "SELECT * FROM `room_images`
                                    WHERE `room_id` = '{$room_data['id']}'
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
                                    $book_btn = "<a onclick='checkLoginToBook($login, {$room_data['id']})' class='btn w-100 btn btn-sm btn-outline-dark shadow-none mb-2'>Book Now</a>";
                                }

                                // Print room card
                                echo "
                            <div class='card mb-4 border-0 shadow'>
                                <div class='row g-0 p-3 align-items-center'>
                                    <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                                        <img src='{$room_thumb}' class='img-fluid rounded'>
                                    </div>
                                    <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                                        <h5 class='mb-3'>{$room_data['name']}</h5>
                                        <div class='features mb-3'>
                                            <h6 class='mb-1'>Features</h6>
                                            {$features_data}
                                        </div>
                                        <div class='facilities mb-3'>
                                            <h6 class='mb-1'>Facilities</h6>
                                            {$facilities_data}
                                        </div>
                                        <div class='guests'>
                                            <h6 class='mb-1'>Guests</h6>
                                            <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                                {$room_data['adult']} Adults
                                            </span>
                                            <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                                {$room_data['children']} Children
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class='mb-1 mt-2'>Hotel Name</h6>
                                            <a href='hotel_details.php?id={$room_data['hotel_id']}' class='text-black badge rounded-pill bg-light text-dark text-wrap'>{$room_data['hotel']}</a>
                                        </div>
                                    </div>
                                    <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 text-center'>
                                        <h6 class='mb-4'>{$room_data['price']} per night</h6>
                                        {$book_btn}
                                        <a href='room_details.php?id={$room_data['id']}' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More Details</a>
                                    </div>
                                </div>
                            </div>";
                            }
                        }
                    } else {
                        $query = "SELECT * FROM `rooms` WHERE `hotel_id`=? AND `status`=? AND `removed`=?";
                        $params = [$id, 1, 0];
                        $types = 'iii';

                        if (!empty($adults)) {
                            $query .= " AND `adult`>=?";
                            $params[] = $adults;
                            $types .= 'i';
                        }

                        if (!empty($children)) {
                            $query .= " AND `children`>=?";
                            $params[] = $children;
                            $types .= 'i';
                        }

                        $room_res = select($query, $params, $types);

                        if (mysqli_num_rows($room_res) == 0) {
                            echo "<p>No rooms available for this hotel.</p>";
                        } else {
                            while ($room_data = mysqli_fetch_assoc($room_res)) {
                                // Check availability logic filter
                                if (!empty($checkin) && !empty($checkout)) {
                                    $tb_query = "SELECT COUNT(*) AS `total_bookings` FROM `booking_order`
                                     WHERE booking_status=? AND room_id=? AND check_out>? AND check_in<?";
                                    $values = ['booked', $room_data['id'], $checkin, $checkout];
                                    $tb_fetch = mysqli_fetch_assoc(select($tb_query, $values, 'siss'));

                                    if (($room_data['quantity'] - $tb_fetch['total_bookings']) == 0) {
                                        continue;
                                    }
                                }

                                $facilities_array = !empty($facilities) ? explode(',', $facilities) : [];
                                $fac_count = 0;
                                $fac_q = mysqli_query($con, "SELECT f.name, f.id FROM `facilities` f
                                INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
                                WHERE rfac.room_id = '{$room_data['id']}'");

                                $facilities_data = "";
                                while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                                    if (in_array($fac_row['id'], $facilities_array)) {
                                        $fac_count++;
                                    }
                                    $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                        {$fac_row['name']}
                                    </span> ";
                                }

                                if (!empty($facilities) && count($facilities_array) != $fac_count) {
                                    continue;
                                }

                                // Get features of rooms
                                $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
                                INNER JOIN `room_features` rfea ON f.id = rfea.features_id
                                WHERE rfea.room_id = '{$room_data['id']}'");

                                $features_data = "";
                                while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                                    $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
                                        {$fea_row['name']}
                                    </span> ";
                                }

                                // Get thumbnail
                                $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
                                $thumb_q = mysqli_query($con, "SELECT * FROM `room_images`
                                WHERE `room_id` = '{$room_data['id']}'
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
                                    $book_btn = "<a onclick='checkLoginToBook($login, {$room_data['id']})' class='btn w-100 btn btn-sm btn-outline-dark shadow-none mb-2'>Book Now</a>";
                                }

                                // Print room card
                                echo "
                                <div class='card mb-4 border-0 shadow'>
                                    <div class='row g-0 p-3 align-items-center'>
                                        <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                                            <img src='{$room_thumb}' class='img-fluid rounded'>
                                        </div>
                                        <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                                            <h5 class='mb-3'>{$room_data['name']}</h5>
                                            <div class='features mb-3'>
                                                <h6 class='mb-1'>Features</h6>
                                                {$features_data}
                                            </div>
                                            <div class='facilities mb-3'>
                                                <h6 class='mb-1'>Facilities</h6>
                                                {$facilities_data}
                                            </div>
                                            <div class='guests'>
                                                <h6 class='mb-1'>Guests</h6>
                                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                                    {$room_data['adult']} Adults
                                                </span>
                                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                                    {$room_data['children']} Children
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class='mb-1 mt-2'>Hotel Name</h6>
                                                <a href='hotel_details.php?id={$room_data['hotel_id']}' class='text-black badge rounded-pill bg-light text-dark text-wrap'>{$room_data['hotel']}</a>
                                            </div>
                                        </div>
                                        <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 text-center'>
                                            <h6 class='mb-4'>{$room_data['price']} per night</h6>
                                            {$book_btn}
                                            <a href='room_details.php?id={$room_data['id']}' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More Details</a>
                                        </div>
                                    </div>
                                </div>";
                            }
                        }
                    }

                    ?>
                </div>

                <div class="mb-5">
                    <h5 class="mb-5">House Rules</h5>
                    <div class="col-md-12" style=" padding: 10px;">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p style="margin-left: 20px;">Check in</p>
                            </div>
                            <div>
                                <p class="text-end" style="margin-right: 900px;">From 14:00</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p style="margin-left: 20px;">Check out</p>
                            </div>
                            <div>
                                <p class="text-end" style="margin-right: 905px;">Until 12:00</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p style="margin-left: 20px;">Cancellation/<br>prepayment</p>
                            </div>
                            <div>
                                <p style="margin-left: 138px;">Cancellation and prepayment policies vary according to accommodation type. Please check what conditions may apply to each option when making your selection.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p style="margin-left: 19px;">No age restriction</p>
                            </div>
                            <div>
                                <p style="margin-right: 660px;">There is no age requirement for check-in</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p style="margin-left: 20px;">Pets</p>
                            </div>
                            <div>
                                <p class="text-end" style="margin-right: 820px;">Pets are not allowed.</p>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div>
                                <p style="margin-left: 20px;">Payment</p>
                            </div>
                            <div>
                                <p class="text-end" style="margin-right: 820px;">Pay in hotel or online.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('filterform').addEventListener('submit', function(event) {
                event.preventDefault();

                var checkinValue = document.querySelector('input[name="checkin_detail"]').value;
                var checkoutValue = document.querySelector('input[name="checkout_dettail"]').value;
                var adultsValue = document.querySelector('input[name="adults_detail"]').value;
                var childrenValue = document.querySelector('input[name="children_detail"]').value;
                var facilitiesValues = [];
                document.querySelectorAll('input[name="facilities[]"]:checked').forEach(function(element) {
                    facilitiesValues.push(element.value);
                });
                var idValue = document.querySelector('input[name="id"]').value;

                window.location.href = "hotel_details.php?id=" + idValue + "&checkin=" + checkinValue + "&checkout=" + checkoutValue + "&adults=" + adultsValue + "&children=" + childrenValue + "&facilities=" + facilitiesValues;


                console.log("Check-in:", checkinValue);
                console.log("Check-out:", checkoutValue);
                console.log("Adults:", adultsValue);
                console.log("Children:", childrenValue);
                console.log("Facilities:", facilitiesValues);
                console.log("ID:", idValue);


            });
        });
    </script>
    




    <?php
    require('inc/footer.php');

    ?>

</body>


</html>