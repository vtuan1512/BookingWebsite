<?php 
session_start();
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');

$email = mysqli_real_escape_string($con, $_POST['email']);
$password = mysqli_real_escape_string($con, $_POST['password']);

if (!empty($email) && !empty($password)) {
    $sql = mysqli_query($con, "SELECT * FROM `user_cred` WHERE `email`='{$email}'");
    if (mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_assoc($sql);
        $enc_pass = $row['password'];
        if (password_verify($password, $enc_pass)) {
            $status_chat = "Online";
            $sql2 = mysqli_query($con, "UPDATE `user_cred` SET `status_chat` = '{$status_chat}' WHERE `email`= '{$email}'");
            if ($sql2) {
                $_SESSION['uId'] = $row['id'];
                echo "success";
            } else {
                echo "Something went wrong. Please try again!";
            }
        } else {
            echo "Password or Email is incorrect";
        }
    } else {
        echo "$email - This email does not exist";
    }
} else {
    echo "Please enter all the fields";
}
?>
