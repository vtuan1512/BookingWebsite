<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title']; ?> - Rooms Details</title>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <?php
    if (!isset($_GET['id'])) {
        redirect('rooms.php');
    }

    $data = filteration($_GET);
    $room_res = select("SELECT * FROM `rooms` WHERE `id`=? AND `status`=? AND `removed`=?", [$data['id'], 1, 0], 'iii');

    if (mysqli_num_rows($room_res) == 0) {
        redirect('rooms.php');
    }
    $room_data = mysqli_fetch_assoc($room_res);
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold"><?php echo $room_data['name']; ?></h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">Rooms</a>
                </div>
            </div>
            <div class="col-lg-7 col-md-12 px-4">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $room_img = ROOMS_IMG_PATH . "thumbnail.jpg";
                        $img_q = mysqli_query($con, "SELECT * FROM `room_images` WHERE `room_id` = '$room_data[id]'");

                        if (mysqli_num_rows($img_q) > 0) {
                            $active_class = 'active';
                            while ($img_res = mysqli_fetch_assoc($img_q)) {
                                echo "
                                    <div class='carousel-item $active_class'>
                                        <img src='" . ROOMS_IMG_PATH . $img_res['image'] . "' class='d-block w-100'>
                                    </div>
                                ";
                                $active_class = '';
                            }
                        } else {
                            echo "<div class='carousel-item active'>
                                    <img src='$room_img' class='d-block w-100'>
                                  </div>";
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <?php
                        echo <<<price
                            <h4>{$room_data['price']} per night</h4>
                        price;

                        $rating_q = "SELECT AVG(rating) AS `avg_rating` FROM `rating_review` WHERE `room_id`='$room_data[id]' ORDER BY `id` DESC LIMIT 20";
                        $rating_res = mysqli_query($con, $rating_q);
                        $rating_fetch = mysqli_fetch_assoc($rating_res);
                        $rating_data = "";

                        if ($rating_fetch['avg_rating'] != NULL) {
                            for ($i = 0; $i < $rating_fetch['avg_rating']; $i++) {
                                $rating_data .= "<i class='bi bi-star-fill text-warning'></i>";
                            }
                        }

                        echo <<<rating
                            <div class="mb-3">
                               $rating_data
                            </div>
                        rating;

                        echo <<<hotel_name
                            <div class="mb-3">
                                <h6 class="mb-1">Hotel Name</h6>
                                <span class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">
                                {$room_data['hotel']}
                                </span>
                            </div>
                        hotel_name;

                        $fea_q = mysqli_query($con, "SELECT f.name FROM `features` f INNER JOIN `room_features` rfea ON f.id = rfea.features_id WHERE rfea.room_id = '$room_data[id]'");

                        $features_data = "";
                        while ($fea_row = mysqli_fetch_assoc($fea_q)) {
                            $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fea_row['name']}</span> ";
                        }
                        echo <<<features
                            <div class="mb-3">
                                <h6 class="mb-1">Features</h6>
                                $features_data
                            </div>
                        features;

                        $fac_q = mysqli_query($con, "SELECT f.name FROM `facilities` f INNER JOIN `room_facilities` rfac ON f.id = rfac.facilities_id WHERE rfac.room_id = '$room_data[id]'");

                        $facilities_data = "";
                        while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                            $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap'>{$fac_row['name']}</span> ";
                        }
                        echo <<<facilities
                            <div class="mb-3">
                                <h6 class="mb-1">Facilities</h6>
                                $facilities_data
                            </div>
                        facilities;

                        echo <<<guest
                            <div class="guests">
                                <h6 class="mb-3">Guests</h6>
                                <span class="badge rounded-pill bg-light text-dark text-wrap">{$room_data['adult']} Adults</span>
                                <span class="badge rounded-pill bg-light text-dark text-wrap">{$room_data['children']} Children</span>
                            </div>
                        guest;

                        echo <<<area
                            <div class="mb-3">
                                <h6 class="mb-1">Area</h6>
                                <span class="badge rounded-pill bg-light text-dark text-wrap me-1 mb-1">{$room_data['area']} m2</span>
                            </div>
                        area;

                        if (!$settings_r['shutdown']) {
                            $login = 0;
                            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                                $login = 1;
                            }
                            echo <<<book
                                <a onclick='checkLoginToBook($login, {$room_data['id']})' class="btn w-100 btn-sm btn-outline-dark shadow-none mb-1">Book Now</a>
                            book;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 px-4">
                <div class="mb-5">
                    <h5>Description</h5>
                    <?php echo htmlspecialchars_decode($room_data['description']); ?>
                </div>
                <div>
                    <div>
                        <h5 class="mb-3">Reviews & Ratings</h5>
                        <form id="reviewForm" class="d-flex mt3 mb-4">
                            <input type="hidden" name="room_id" value="<?php echo $room_data['id']; ?>">
                            <input type="text" name="review" class="form-control me-2" placeholder="Write your review...">
                            <button type="submit" class="btn btn-outline-dark text-white bg-dark">Submit</button>
                        </form>
                        <div id="reviewsList"></div> 
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            getAllComments(<?php echo $room_data['id']; ?>);

                            document.getElementById("reviewForm").addEventListener("submit", function(event) {
                                event.preventDefault(); 

                                var formData = new FormData(this);
                                var xhr = new XMLHttpRequest();
                                xhr.open("POST", "ajax/review_room.php", true);
                                xhr.onload = function() {
                                    if (xhr.status == 200) {
                                        getAllComments(<?php echo $room_data['id']; ?>); 
                                        document.getElementById("reviewForm").reset(); 
                                    } else {
                                        console.error("Error: " + xhr.statusText);
                                    }
                                };
                                xhr.onerror = function() {
                                    console.error("Request failed.");
                                };
                                xhr.send(formData);
                            });
                        });

                        function getAllComments(roomId) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("GET", "ajax/review_room.php?room_id=" + roomId, true);
                            xhr.onload = function() {
                                if (xhr.status == 200) {
                                    document.getElementById("reviewsList").innerHTML = xhr.responseText;
                                } else {
                                    console.error("Error: " + xhr.statusText);
                                }
                            };
                            xhr.onerror = function() {
                                console.error("Request failed.");
                            };
                            xhr.send();
                        }
                    </script>


                    <?php
                    $review_q = "SELECT rr.*, uc.name AS uname, uc.profile, r.name AS rname 
                                FROM `rating_review` rr 
                                INNER JOIN `user_cred` uc ON rr.user_id = uc.id 
                                INNER JOIN `rooms` r ON rr.room_id = r.id 
                                WHERE rr.room_id = '$room_data[id]' ORDER BY rr.id DESC LIMIT 10";

                    $review_res = mysqli_query($con, $review_q);
                    $img_path = USERS_IMG_PATH;
                    if (mysqli_num_rows($review_res) == 0) {
                        echo 'No reviews yet!';
                    } else {
                        // echo '<div id="reviewsList">';
                        // while ($row = mysqli_fetch_assoc($review_res)) {
                        //     $stars = "";
                        //     for ($i = 0; $i < $row['rating']; $i++) {
                        //         $stars .= "<i class='bi bi-star-fill text-warning'></i>";
                        //     }
                        //     $current_time = new DateTime();
                        //     $comment_time = new DateTime($row['datentime']);
                        //     $interval = $current_time->diff($comment_time);
                        //     if ($interval->y > 0) {
                        //         $time_ago = $interval->y . ' year ago';
                        //     } elseif ($interval->m > 0) {
                        //         $time_ago = $interval->m . ' month ago';
                        //     } elseif ($interval->d > 0) {
                        //         $time_ago = $interval->d . ' day ago';
                        //     } elseif ($interval->h > 0) {
                        //         $time_ago = $interval->h . ' hour ago';
                        //     } elseif ($interval->i > 0) {
                        //         $time_ago = $interval->i . ' minutes ago';
                        //     } else {
                        //         $time_ago = 'now';
                        //     }

                        //     echo '<div>';
                        //     echo '<div class="d-flex align-items-center mb-2">';
                        //     echo '<img src="' . $img_path . $row['profile'] . '" class="rounded-circle" loading="lazy" width="30px">';
                        //     echo '<h6 class="m-0 ms-2">' . $row['uname'] . '</h6>';
                        //     echo '<p class="text-end ms-2 m-0">' . $time_ago . '</p>';
                        //     echo '<div class="rating text-end ms-2">' . $stars . '</div>';
                        //     echo '</div>';
                        //     echo '<p class="mb-4">' . $row['review'] . '</p>';
                        //     echo '</div>';
                        // }

                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
</body>


</html>