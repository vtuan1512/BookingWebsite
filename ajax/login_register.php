<?php

require('../admin/inc/essentials.php');
require('../admin/inc/db_config.php');
session_start();
date_default_timezone_set("Asia/Ho_Chi_Minh");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendMail($email, $token, $type)
{

    if ($type == "email_confirmation") {
        $page = 'email_confirm.php';
        $subject = "Account Verification Link";
        $content = "confirm your account";
    } else {
        $page = 'index.php';
        $subject = "Account Reset Link";
        $content = "reset your account";
    }

    require("./PHPMailer/PHPMailer.php");
    require("./PHPMailer/Exception.php");
    require("./PHPMailer/SMTP.php");

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com;';
        $mail->SMTPAuth = true;
        $mail->Username = 'tuanva.ba11-097@st.usth.edu.vn';
        $mail->Password = 'mxpccpbvqmwshdos';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom(SEND_MAIL, SEND_MAIL_NAME);
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject =  $subject;
        $mail->Body = "
                    Click the link to $content
                    <a href='" . SITE_URL . "/$page?$type&email=$email&token=$token'>
                    Click me</a>
                ";



        $mail->send();
        echo 'Message has been sent';
        return 1;
    } catch (Exception $e) {

        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        return 0;
    }
}

if (isset($_POST['register'])) {
    $data = filteration($_POST);

    //check password and confirm password
    if ($data['pass'] != $data['cpass']) {
        echo 'password_mismatch';
        exit();
    }

    //check user exits or not
    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE `email`=? OR `phonenum`=? LIMIT 1",
        [$data['email'], $data['phonenum']],
        "ss"
    );

    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit();
    }

    // upload user image to server

    $img = uploadUserImage($_FILES['profile']);

    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }

    // send confimation link to user's email
    $token = bin2hex(random_bytes(16));

    if (!sendMail($data['email'], $token, "email_confirmation")) {
        echo 'mail_failed';
        exit;
    }

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    $dob = $data['dob'];
    $dob_date = date('Y-m-d', strtotime($dob));

    $query = "INSERT INTO `user_cred`(`role`,`name`, `email`, `address`, `phonenum`, 
    `pincode`, `dob`,`profile` ,`password`,`token`) 
    VALUES (?,?,?,?,?,?,?,?,?,?)";
    $role = 2;
    $values = [$role,
        $data['name'], $data['email'], $data['address'], $data['phonenum'], $data['pincode'], $dob_date, $img,
        $enc_pass, $token
    ];

    if (insert($query, $values, 'issssissss')) {
        echo 1;
    } else {
        echo 'insert_failed';
    }
}

if (isset($_POST['login'])) {
    $data = filteration($_POST);

    // check user exists or not
    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE `role` = ? AND (`email` = ? OR `phonenum` = ?) LIMIT 1",
        [2, $data['email_mob'], $data['email_mob']],
        "iss" 
    );
    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email_mob';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } elseif ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            if (!password_verify($data['pass'], $u_fetch['password'])) {
                echo 'invalid_pass';
            } else {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPic'] = $u_fetch['profile'];
                $_SESSION['uPhone'] = $u_fetch['phonenum'];

                // Update status_chat to 'Online'
                $update_status = mysqli_query($con, "UPDATE `user_cred` SET `status_chat` = 'Online' WHERE `id` = {$u_fetch['id']}");
                if ($update_status) {
                    echo 1;
                } else {
                    echo 'update_failed';
                }
            }
        }
    }
}

if (isset($_POST['forgot_pass'])) {
    $data = filteration($_POST);

    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE `email`=?  LIMIT 1",
        [$data['email']],
        "s"
    );

    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            //send reset link to email
            $token = bin2hex(random_bytes(16));
            if (!sendMail($data['email'], $token, 'account_recovery')) {
                echo 'mail_failed';
            } else {
                $date = date("Y-m-d");
                $query = mysqli_query($con, "UPDATE `user_cred` SET `token`='$token',`t_expire`='$date' WHERE `id`='$u_fetch[id]'");

                if ($query) {
                    echo 1;
                } else {
                    echo 'upd_failed';
                }
            }
        }
    }
}

if (isset($_POST['recover_user'])) {
    $data = filteration($_POST);
    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);
    $date = date("Y-m-d");
    $new_token = bin2hex(random_bytes(16));
    $query =  "UPDATE `user_cred` SET `password`=?, `token`=?, `t_expire`=? WHERE `email`=? AND `token`=? ";

    $values = [$enc_pass, $new_token, $date, $data['email'], $data['token']];

    if (update($query, $values, 'sssss')) {
        echo 1;
    } else {
        echo 'failed';
    }
}
