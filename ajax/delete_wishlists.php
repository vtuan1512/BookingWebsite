<?php
session_start();
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (isset($_SESSION['uId'])) {
    $hotelId = isset($_POST['hotelId']) ? $_POST['hotelId'] : null;
    $userId = $_SESSION['uId'];

    if ($hotelId) {
        $delete_query = "DELETE FROM `wishlist` WHERE `user_id` = ? AND `hotel_id` = ?";
        $delete_result = delete($delete_query, [$userId, $hotelId], 'ii');

        if ($delete_result) {
            echo "success";
        } else {
            echo "error";
        }
    } else {
        // echo "error";
    }
} else {
    echo "not_logged_in";
}


if (isset($_POST['get_wishlist'])) {
    $userId = isset($_SESSION['uId']) ? $_SESSION['uId'] : null;

    if ($userId) {
        $wishlist_query = "SELECT hotel_id FROM wishlist WHERE user_id = ?";
        $wishlist_result = select($wishlist_query, [$userId], 'i');

        if (mysqli_num_rows($wishlist_result) > 0) {
            echo "<div class='row'>";
            while ($row = mysqli_fetch_assoc($wishlist_result)) {
                $hotel_id = $row['hotel_id'];
                $hotel_query = "SELECT * FROM `hotel` WHERE `id` = ?";
                $hotel_result = select($hotel_query, [$hotel_id], 'i');
                $hotel_thumb = HOTEL_IMG_PATH;
                if ($hotel_data = mysqli_fetch_assoc($hotel_result)) {
                    echo "<div class='card mb-4 border-0 shadow mt-3'>
                                            <div class='row g-0 p-3 align-items-center'>
                                                <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                                                <img src='$hotel_thumb$hotel_data[image]' class='img-fluid rounded w-100' style='height: 300px;'>
                                                </div>
                                                <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                                                    <h5 class='mb-3'>$hotel_data[name]</h5> 
                                                    <div class='guests'>
                                                        <h6 class='mb-1'>
                                                            Address
                                                        </h6>
                                                        <span class='badge rounded-pill bg-light text-dark text-wrap mb-1'>
                                                            $hotel_data[address] 
                                                        </span>
                                                        <h6 class='mb-1'>
                                                            Contact
                                                        </h6>
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
                                                    <a href='hotel_details.php?id=$hotel_data[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none mt-2'>Book Now</a>
                                                    <button type='button' class='btn btn-sm w-100 btn-outline-dark shadow-none mt-2 delete-wishlist-btn' data-hotel-id='$hotel_data[id]'><i class='bi bi-trash'></i>Delete</button>
                                                </div>
                                            </div>
                                        </div>";
                }
            }
            echo "</div>";
        } else {
            echo "<h5 class='text-warning'>No hotels in your wishlist.</h5>";
        }
    } else {
        echo "<p>Please log in to view your wishlist.</p>";
    }
}
