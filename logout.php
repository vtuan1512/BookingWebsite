<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php'); // Kết nối cơ sở dữ liệu

session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['uId'])) {
    $user_id = $_SESSION['uId'];

    // Cập nhật trạng thái `status_chat` thành "Offline"
    $update_status = mysqli_query($con, "UPDATE `user_cred` SET `status_chat` = 'Offline now' WHERE `id` = {$user_id}");
    if (!$update_status) {
        // Nếu cập nhật thất bại, bạn có thể xử lý lỗi tại đây
        die('Failed to update status');
    }
}

// Hủy phiên làm việc (session)
session_destroy();

// Chuyển hướng về trang index
redirect('index.php');
?>
