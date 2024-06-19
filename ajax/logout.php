<?php
session_start();
if (isset($_SESSION['uId'])) {
     require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');

    $logout_id = mysqli_real_escape_string($con, $_GET['logout_id']);

    if (isset($logout_id)) {
        $status = "Offline now";
        $sql = mysqli_query($con, "UPDATE user_cred SET status_chat = '{$status}' WHERE id = {$logout_id}");

        if ($sql) {
            session_unset();
            session_destroy();
            header("location: ../index.php");
        }
    }else{
        header("location: ../users.php");
    }
}
else{
    header("location: ../index.php");
}
?>
