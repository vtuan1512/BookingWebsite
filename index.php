<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="/images/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/bootstrap-5.3.3/dist/css/bootstrap.min.css">

    <style>
        .availability-form {
            z-index: 1000;
            position: relative;

        }

        @media screen and (max-width: 575px) {
            .availability-form {
                /* margin-top: -50px; */
                padding: 0 35px;
            }

        }
    </style>
    <script src="swiper.js"></script>

    <script>
        import Swiper from 'swiper/bundle';

        // import styles bundle
        import 'swiper/css/bundle';

        function alert(type, msg, position = 'body') {
            let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
            let element = document.createElement('div');
            element.innerHTML = `
            <div class="alert ${bs_class}  alert-dismissible fade show" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
            if (position == 'body') {
                document.body.append(element);
                element.classList.add('custom-alert');
            } else {
                document.getElementById(position).appendChild(element);
            }
            setTimeout(remAlert, 3000);
        }

        function remAlert() {
            document.getElementsByClassName('alert')[0].remove();
        }
    </script>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <!-- Swiper -->
    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                $res = selectAll('carousel');

                while ($row = mysqli_fetch_assoc($res)) {
                    $path = CAROUSEL_IMG_PATH;

                    echo <<<data
                        <div class="swiper-slide">
                            <img src="$path$row[image]" class="w-100 d-block" height="600" />
                        </div>
                        data;
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Check availability form -->
    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12-bg-white shadow p-4 rounded">
                <h5 class="mb-4">Check Availability</h5>
                <form action="hotels.php">
                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Check-in</label>
                            <input type="date" class="form-control shadow-none" name="checkin" required>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Check-out</label>
                            <input type="date" class="form-control shadow-none" name="checkout" required>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Adult</label>
                            <select class="form-select shadow-none" name="adult">
                                <?php
                                $guests_q = mysqli_query($con, "SELECT MAX(adult) AS `max_adult`, MAX(children) AS `max_children` 
                                    FROM `rooms` WHERE `status`='1' AND `removed`='0'");
                                $guests_res = mysqli_fetch_assoc($guests_q);

                                for ($i = 1; $i <= $guests_res['max_adult']; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>

                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">Children</label>
                            <select class="form-select shadow-none" name="children">
                                <?php
                                for ($i = 1; $i <= $guests_res['max_children']; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <input type="hidden" name="check_availability">
                        <div class="col-lg-1 mb-lg-3 mt-2">
                            <button type="submit" class="btn  btn-outline-dark shadow-none">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Our Rooms -->
    <!-- <h2 class="mt-5 pt-4 mb-4 text-center fw-bold ">Special Rooms</h2>
    <div class="container">
        <div class="row">
            <?php
            // $room_res = select("SELECT * FROM `rooms` WHERE `status`=? AND `removed`=?  ORDER BY `id` DESC LIMIT 3", [1, 0], 'ii');
            // while ($room_data = mysqli_fetch_assoc($room_res)) {
            //     //get features of rooms
            //     $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f 
            //             INNER JOIN `room_features` rfea ON f.id = rfea.features_id
            //             WHERE rfea.room_id = '$room_data[id]'");

            //     $features_data = "";
            //     while ($fea_row = mysqli_fetch_assoc($fea_q)) {
            //         $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
            //                     $fea_row[name]
            //                 </span> ";
            //     }
            //     // get facilities of room

            //     $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f
            //             INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id 
            //             WHERE rfac.room_id = '$room_data[id]'");

            //     $facilities_data = "";
            //     while ($fac_row = mysqli_fetch_assoc($fac_q)) {
            //         $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>
            //                     $fac_row[name]
            //                 </span> ";
            //     }

            //     // get thumbnail
            //     $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";
            //     $thumb_q = mysqli_query($con, "SELECT * FROM `room_images`
            //             WHERE `room_id` = '$room_data[id]'
            //             AND `thumb`='1'");

            //     if (mysqli_num_rows($thumb_q) > 0) {
            //         $thumb_res = mysqli_fetch_assoc($thumb_q);
            //         $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];
            //     }

            //     $book_btn = "";
            //     if (!$settings_r['shutdown']) {
            //         $login = 0;
            //         if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
            //             $login = 1;
            //         }
            //         $book_btn = "<a onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm btn-outline-dark shadow-none'>Book Now</a>";
            //     }

            //     $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review`
            //     WHERE `room_id`='$room_data[id]' ORDER BY `id` DESC LIMIT 20";

            //     $rating_res = mysqli_query($con, $rating_q);
            //     $rating_fetch = mysqli_fetch_assoc($rating_res);
            //     $rating_data = "";

            //     if ($rating_fetch['avg_rating'] != NULL) {
            //         $rating_data = "<div class='rating mb-4'>
            //                     <h6 mb-1>Rating</h6>
            //                     <span class='badge rounded-pill bg-light'>
            //                     ";
            //         for ($i = 0; $i < $rating_fetch['avg_rating']; $i++) {
            //             $rating_data .= "<i class='bi bi-star-fill text-warning'></i>";
            //         }

            //         $rating_data .= "</span>
            //         </div>
            //         ";
            //     }


            //     // print room card
            //     echo <<< data
            //         <div class="col-lg-4 col-md-6 my-3">
            //         <div class="card border-0 shadow" style="max-width: 350px;margin:auto;">
            //             <img src="$room_thumb" class="card-img-top">

            //             <div class="card-body">
            //                 <h5>$room_data[name]</h5>
            //                 <h6 class="mb-4">$room_data[price] per night</h6>
            //                 <div class="features mb-4">
            //                     <h6 class="mb-1">Features</h6>
            //                     $features_data
            //                 </div>
            //                 <div class="facilities mb-4">
            //                     <h6 class="mb-1">Facilities</h6>
            //                     $facilities_data
            //                 </div>
            //                 <div class="guests mb-4">
            //                     <h6 class="mb-1">
            //                         Guest
            //                     </h6>
            //                     <span class="badge rounded-pill bg-light text-dark text-wrap">
            //                         $room_data[adult] Adults
            //                     </span>
            //                     <span class="badge rounded-pill bg-light text-dark text-wrap">
            //                         $room_data[children] Children
            //                     </span>
            //                 </div>
            //                     $rating_data
            //                 <div class="d-flex justify-content-evenly mb-2">
            //                     $book_btn
            //                     <a href="room_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none">More Details</a>
            //                 </div>
            //             </div>

            //             </div>
            //         </div>
            //         data;
            // }
            ?>
            <div class="col-lg-12 text-center mt-5">
                <a href="hotels.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More Rooms</a>
            </div>
        </div>
    </div> -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold ">Special Rooms</h2>
    <div class="container">
        <div class="row">
            <?php
            $room_res = select("SELECT * FROM `hotel` WHERE `status`=? AND `removed`=?  ORDER BY `id` DESC LIMIT 3", [1, 0], 'ii');
            $path = HOTEL_IMG_PATH;
            while ($room_data = mysqli_fetch_assoc($room_res)) {
                // print room card
                echo <<< data
                    <div class="col-lg-4 col-md-6 my-3">
                    <div class="card border-0 shadow" style="max-width: 350px;margin:auto;">
                        <img src="$path$room_data[image]" class="card-img-top">

                        <div class="card-body">
                            <h5>$room_data[name]</h5>
                            <h6 class="mb-4">$room_data[address]</h6>
                            <div class="features mb-4">
                                <h6 class="mb-1">Phone Number:</h6>
                                +84$room_data[phone]
                            </div>
                            <div class="facilities mb-4">
                                <h6 class="mb-1">Email:</h6>
                                $room_data[email]
                            </div>
                            <div class="facilities mb-4">
                                <a href="hotel_details.php?id=$room_data[id]" class="btn btn-sm btn-outline-dark shadow-none text-center" >More Details</a>
                            </div>
                            
                        </div>

                        </div>
                    </div>
                    data;
            }
            ?>
            <div class="col-lg-12 text-center mt-5">
                <a href="hotels.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More hotels</a>
            </div>
        </div>
    </div>
    <!-- destination -->
    <!-- <h2 class="mt-5 pt-4 mb-4 text-center fw-bold">Beautiful place in Da Lat</h2>
    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            <?php
            $query = "SELECT * FROM `destination` LIMIT 5";
            $result = mysqli_query($con, $query);
            $path = RCM_POST_IMG_PATH;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow ">
                    <a href="recommend.php" class="text-decoration-none">
                        <img src="<?php echo $path . $row['image']; ?>" width="200px" height="170px">
                        <h5 class="mt-3" style="color: black;"><?php echo $row['name']; ?></h5>
                    </a>
                </div>

            <?php
            }
            ?>

            <div class="col-lg-12 text-center mt-5">
                <a href="recommend.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More blogs</a>
            </div>
        </div>
    </div> -->
    <!-- food -->
    <!-- <h2 class="mt-5 pt-4 mb-4 text-center fw-bold ">Signature food in Da Lat</h2>
    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            <?php
            $query = "SELECT * FROM `food` LIMIT 5";
            $result = mysqli_query($con, $query);
            $path = FOOD_IMG_PATH;
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow ">
                    <a href="recommend.php" class="text-decoration-none">
                        <img src="<?php echo $path . $row['image']; ?>" width="200px" height="170px">
                        <h5 class="mt-3" style="color: black;"><?php echo $row['name']; ?></h5>
                    </a>
                </div>
            <?php
            }
            ?>

            <div class="col-lg-12 text-center mt-5">
                <a href="recommend.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">More blogs</a>
            </div>
        </div>

    </div> -->

    <!-- testimonials -->
    <!-- <h2 class="mt-5 pt-4 mb-4 text-center fw-bold ">Rating & Comment</h2> -->
    <!-- <div class="container">
        <div class="swiper testimonials">
            <div class="swiper-wrapper mb-5">
                <?php
                // $review_q =  "SELECT rr.*, uc.name AS uname, uc.profile, r.name AS rname 
                // FROM `rating_review` rr 
                // INNER JOIN `user_cred` uc ON rr.user_id = uc.id
                // INNER JOIN `rooms` r ON rr.room_id = r.id
                // ORDER BY rr.id DESC LIMIT 6";


                // $review_res = mysqli_query($con, $review_q);
                // $img_path = USERS_IMG_PATH;
                // if (mysqli_num_rows($review_res) == 0) {
                //     echo 'No reviews yet!';
                // } else {
                //     while ($row = mysqli_fetch_assoc($review_res)) {
                //         $stars = "";
                //         for ($i = 1; $i < ['rating']; $i++) {
                //             $stars .= " <i class='bi bi-star-fill text-warning'></i>";
                //         }
                //         echo <<<slides
                //         <div class="swiper-slide bg-white p-4">
                //             <div class="profile d-flex align-items-center mb-3">
                //                 <img src="$img_path$row[profile]" class="rounded circle" loading="lazy" width="30px">
                //                 <h6 class="m-0 ms-2">$row[uname]</h6>
                //             </div>
                //             <p>
                //                 $row[review]
                //             </p>
                //             <div class="rating">
                //                $stars
                //             </div>
                //         </div>
                //             <div class="swiper-pagination"></div>
                //         slides;
                //     }
                // }

                ?>
            </div>
            ?>
        </div>
        <div class="col-lg-12 text-center mt-5">
            <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Know more</a>
        </div>
    </div> -->

    <!-- Reach us -->
    <!-- <h2 class="mt-5 pt-4 mb-4 text-center fw-bold ">About us</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded ">
                <iframe class="w-100 rounded" height="320px" src="<?php echo $contact_r['iframe'] ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="bg-white p-4 rounded mb-4">
                    <h5> Call us</h5>
                    <a href="tel: +<?php echo $contact_r['pn1'] ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone"></i> +<?php echo $contact_r['pn1'] ?>
                    </a>
                    <br>
                    <?php
                    if ($contact_r['pn2'] != '') {
                        echo <<<data
                            <a href="tel: +$contact_r[pn2]" class="d-inline-block text-decoration-none text-dark">
                                <i class="bi bi-telephone"></i> +$contact_r[pn2]
                            </a>
                            data;
                    }
                    ?>

                </div>
                <div class="bg-white p-4 rounded mb-4">
                    <h5> Follow us</h5>
                    <?php
                    if ($contact_r['tw'] != '') {
                        echo <<<data
                            <a href="$contact_r[tw]" class="d-inline-block mb-3">
                                <span class="badge bg-light text-dark fs-6 p-s">
                                    <i class="bi bi-twitter me-1"></i> Twitter
                                </span>
                            </a>
                            <br>
                            data;
                    }
                    ?>
                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3">
                        <span class="badge bg-light text-dark fs-6 p-s">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </span>
                    </a>
                    <br>
                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block ">
                        <span class="badge bg-light text-dark fs-6 p-s">
                            <i class="bi bi-instagram me-1"></i> Instagram
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div> -->


    <!-- Password reset modal and code  -->

    <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="recovery-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-shield-lock fs-3 me-2"></i> Set up new Password
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">New Password </label>
                            <input type="password" name="pass" class="form-control shadow-none">
                            <input type="hidden" name="email">
                            <input type="hidden" name="token">
                        </div>
                        <!-- <div class="mb-4">
                        <label class="form-label">Confirm new Password </label>
                        <input type="password" name="cpass" class="form-control shadow-none">
                    </div> -->
                        <div class="mb-2 text-end">
                            <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-dark shadow-none ">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <?php

    if (isset($_GET['account_recovery'])) {
        $data = filteration($_GET);
        $t_date = date("Y-m-d");
        $query = select(
            "SELECT * FROM `user_cred` WHERE `email`=? AND `token`=? AND `t_expire`=? LIMIT 1",
            [$data['email'], $data['token'], $t_date],
            'sss'
        );

        if (mysqli_num_rows($query) == 1) {
            echo <<< showModal
            <script>
                var myModal = document.getElementById('recoveryModal');

                myModal.querySelector("input[name='email']").value = "{$data['email']}";
                myModal.querySelector("input[name='token']").value = "{$data['token']}";
                var modal = bootstrap.Modal.getOrCreateInstance(myModal);
                modal.show();
            </script>
            showModal;
        } else {
            alert("error", "Invalid or Expired Link !");
        }
    }

    ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".swiper-container", {
            spaceBetween: 30,
            effect: "fade",
            loop: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            }
        });
        var swiper = new Swiper(".testimonials", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            slidesPerView: "auto",
            slidesPerView: '3',
            loop: true,
            coverflowEffect: {
                rotate: 50,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoint: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },

            }
        });

        // recover account

        let recovery_form = document.getElementById('recovery-form');
        recovery_form.addEventListener('submit', (e) => {
            e.preventDefault();
            let data = new FormData();

            data.append('email', recovery_form.elements['email'].value);
            data.append('token', recovery_form.elements['token'].value);
            data.append('pass', recovery_form.elements['pass'].value);
            data.append('recover_user', '');

            var myModal = document.getElementById('recoveryModal');
            var modal = bootstrap.Modal.getInstance(myModal);
            modal.hide();

            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/login_register.php', true);



            xhr.onload = function() {
                if (this.responseText == 'failed') {
                    alert('error', "Account reset password failed !");
                    console.log('failed')
                } else {
                    alert('success', " Account Reset Successful !");
                    console.log('success')
                    recovery_form.reset();
                }
            }

            xhr.send(data);

        });
    </script>
</body>

</html>