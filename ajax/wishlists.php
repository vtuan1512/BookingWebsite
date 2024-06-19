<?php
session_start();
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (isset($_SESSION['uId'])) {
    $hotelId = isset($_POST['hotelId']) ? $_POST['hotelId'] : null;
    $userId = $_SESSION['uId'];

    if ($hotelId) {
        $check_query = "SELECT COUNT(*) AS count FROM `wishlist` WHERE `user_id` = ? AND `hotel_id` = ?";
        $check_result = select($check_query, [$userId, $hotelId], 'ii');
        $existing_count = mysqli_fetch_assoc($check_result)['count'];

        if ($existing_count == 0) {
            $insert_query = "INSERT INTO `wishlist`(`user_id`, `hotel_id`) VALUES (?, ?)";
            $insert_result = insert($insert_query, [$userId, $hotelId], 'ii');

            if ($insert_result) {
                echo "done";
            } else {
                echo "error";
            }
        } else {
            echo "already_added";
        }
    } else {
        echo "error";
    }
} else {
    echo "not_logged_in";
}
