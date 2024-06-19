<?php
require('../admin/inc/essentials.php');
require('../admin/inc/db_config.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['review_form'])) {
        $frm_data = filteration($_POST);
        $upd_query = "UPDATE `booking_order` SET `rate_review`=? 
        WHERE `booking_id`=? AND `user_id`=?";
        $upd_values = [1, $frm_data['booking_id'], $_SESSION['uId']];
        $upd_result = update($upd_query, $upd_values, 'iii');
        $ins_query = "INSERT INTO `rating_review`(`booking_id`, `room_id`, `user_id`, `rating`, `review`) 
            VALUES (?,?,?,?,?)";
        $ins_values = [
            $frm_data['booking_id'],
            $frm_data['room_id'],
            $_SESSION['uId'],
            $frm_data['rating'],
            $frm_data['review']
        ];
        $ins_result = insert($ins_query, $ins_values, 'iiiis');
        echo $ins_result;
    } else {
        $room_id = $_POST['room_id'];
        $review = $_POST['review'];
        $user_id = $_SESSION['uId'];

        $insert_review_q = "INSERT INTO `rating_review` (`room_id`, `user_id`, `review`, `booking_id`) VALUES ('$room_id', '$user_id', '$review', NULL)";
        if (mysqli_query($con, $insert_review_q)) {
            echo "Review submitted successfully!";
        } else {
            echo "Error: " . mysqli_error($con);
        }

        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];
    $query = "SELECT rr.*, uc.name AS uname, uc.profile
              FROM `rating_review` rr 
              INNER JOIN `user_cred` uc ON rr.user_id = uc.id 
              WHERE rr.room_id = ?
              ORDER BY rr.id DESC LIMIT 10";
    $statement = $con->prepare($query);
    $statement->bind_param("i", $room_id);
    $statement->execute();
    $result = $statement->get_result();
    $img_path = USERS_IMG_PATH;
    while ($row = $result->fetch_assoc()) {
        $current_time = new DateTime();
        $comment_time = new DateTime($row['datentime']);
        $interval = $current_time->diff($comment_time);
        if ($interval->y > 0) {
            $time_ago = $interval->y . ' year ago';
        } elseif ($interval->m > 0) {
            $time_ago = $interval->m . ' month ago';
        } elseif ($interval->d > 0) {
            $time_ago = $interval->d . ' day ago';
        } elseif ($interval->h > 0) {
            $time_ago = $interval->h . ' hour ago';
        } elseif ($interval->i > 0) {
            $time_ago = $interval->i . ' minutes ago';
        } else {
            $time_ago = 'now';
        }
        echo '<div class="d-flex align-items-center mb-2">';
        echo '<img src="' . $img_path . $row['profile'] . '" class="rounded-circle" loading="lazy" width="30px">';
        echo '<h6 class="m-0 ms-2">' . $row['uname'] . '</h6>';
        echo '<p class="text-end ms-2 m-0">' . $time_ago . '</p>';
        echo '<div class="rating text-end ms-2">' . str_repeat('<i class="bi bi-star-fill text-warning"></i>', $row['rating']) . '</div>';
        echo '</div>';
        echo '<p class="mb-4">' . $row['review'] . '</p>';
    }
}
