<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Rooms</title>
</head>

<body class="bg-light">
    <?php
    require('inc/header.php');

    $checkin_default = "";
    $checkout_default = "";
    $children_default = "";
    $adult_default = "";

    if (isset($_GET['check_availability'])) {
        $frm_data = filteration($_GET);
        $checkin_default = $frm_data['checkin'];
        $checkout_default = $frm_data['checkout'];
        $children_default = $frm_data['children'];
        $adult_default = $frm_data['adult'];
    }
    ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2 text-center">FILTERS</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filtersDropdown">
                            <!-- Check availability -->
                            <div class="border bg-light mb-3 rounded p-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span> Check Availability</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <label class="form-label" style="font-weight: 500;">Check-in</label>
                                <input type="date" class="form-control shadow-none mb-3" value="<?php echo $checkin_default ?>" id="checkin" onchange="chk_avail_filter()">
                                <label class="form-label" style="font-weight: 500;">Check-out</label>
                                <input type="date" class="form-control shadow-none" value="<?php echo $checkout_default ?>" id="checkout" onchange="chk_avail_filter()">
                            </div>
                            <!-- Facilities -->
                            <div class="border bg-light mb-3 rounded p-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span> FACILITIES</span>
                                    <button id="facilities_btn" onclick="facilities_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <?php
                                $facilities_q = selectAll('facilities');
                                while ($row = mysqli_fetch_assoc($facilities_q)) {
                                    echo <<<facilities
                                        <div class="mb-2">
                                            <input type="checkbox" onclick="fetch_rooms()" name="facilities" value="$row[id]" id="$row[id]" class="form-check-input shadow-none me-1">
                                            <label class="form-check-label" for="$row[id]">$row[name]</label>
                                        </div>
                                    facilities;
                                }
                                ?>
                            </div>
                            <!-- Guests -->
                            <div class="border bg-light mb-3 rounded p-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3" style="font-size: 18px;">
                                    <span> GUESTS</span>
                                    <button id="guests_btn" onclick="guests_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">Adults</label>
                                        <input type="number" min="1" id="adults" value="<?php echo $adult_default ?>" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                    <div>
                                        <label class="form-label">Children</label>
                                        <input type="number" min="0" id="children" value="<?php echo $checkout_default ?>" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                </nav>
            </div>
            <div class="col-lg-9 col-md-12 px-4" id="rooms-data"></div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let rooms_data = document.getElementById('rooms-data');
            let checkin = document.getElementById('checkin_detail');
            let checkout = document.getElementById('checkout_detail');
            let chk_avail_btn = document.getElementById('chk_avail_btn');
            let adults = document.getElementById('adults_detail');
            let children = document.getElementById('children_detail');
            let guests_btn = document.getElementById('guests_btn');
            let facilities_btn = document.getElementById('facilities_btn');

            function fetch_rooms() {
                let chk_avail = JSON.stringify({
                    checkin: checkin.value,
                    checkout: checkout.value
                });

                let guests = JSON.stringify({
                    adults: adults.value,
                    children: children.value
                });

                let facility_list = {
                    facilities: []
                };
                let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
                if (get_facilities.length > 0) {
                    get_facilities.forEach((facility) => {
                        facility_list.facilities.push(facility.value);
                    });
                    facilities_btn.classList.remove('d-none');
                } else {
                    facilities_btn.classList.add('d-none');
                }
                let facilities = JSON.stringify(facility_list);

                let xhr = new XMLHttpRequest();
                xhr.open("GET", "ajax/rooms.php?fetch_rooms&chk_avail=" + chk_avail + "&guests=" + guests + "&facility_list=" + facilities, true);

                xhr.onprogress = function() {
                    rooms_data.innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" id="loader" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>`;
                };

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        rooms_data.innerHTML = this.responseText;
                    } else {
                        rooms_data.innerHTML = "<h3 class='text-center text-danger'>Error loading rooms</h3>";
                    }
                };
                xhr.send();
            }

            function chk_avail_filter() {
                if (checkin.value !== '' && checkout.value !== '') {
                    fetch_rooms();
                    chk_avail_btn.classList.remove('d-none');
                }
            }

            function chk_avail_clear() {
                checkin.value = '';
                checkout.value = '';
                chk_avail_btn.classList.add('d-none');
                fetch_rooms();
            }

            function guests_filter() {
                if (adults.value > 0 || children.value > 0) {
                    fetch_rooms();
                    guests_btn.classList.remove('d-none');
                }
            }

            function guests_clear() {
                adults.value = '';
                children.value = '';
                guests_btn.classList.add('d-none');
                fetch_rooms();
            }

            function facilities_clear() {
                let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
                get_facilities.forEach((facility) => {
                    facility.checked = false;
                });
                facilities_btn.classList.add('d-none');
                fetch_rooms();
            }

            // Add event listeners for the date inputs
            checkin.addEventListener('change', chk_avail_filter);
            checkout.addEventListener('change', chk_avail_filter);
            // Add event listeners for the guest inputs
            adults.addEventListener('input', guests_filter);
            children.addEventListener('input', guests_filter);

            // Make functions globally accessible
            window.fetch_rooms = fetch_rooms;
            window.chk_avail_filter = chk_avail_filter;
            window.chk_avail_clear = chk_avail_clear;
            window.guests_filter = guests_filter;
            window.guests_clear = guests_clear;
            window.facilities_clear = facilities_clear;

            // Initial fetch
            window.onload = function(){
                fetch_rooms();
            }
        });
    </script>

    <?php require('inc/footer.php'); ?>

</body>

</html>