<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

if (isset($_POST['info_form'])) 
{
    $frm_data = filteration($_POST);
    session_start();
    $u_exist = select(
        "SELECT * FROM `user_cred` WHERE `phonenum`=? AND `id`!=? LIMIT 1",
        [$frm_data['phonenum'], $_SESSION['managerId']],"ss");

    if (mysqli_num_rows($u_exist) != 0) {
        echo 'phone_already';
        exit();
    }

    $query = "UPDATE `user_cred` SET `name`=?, `address`=?, `phonenum`=?, `pincode`=?, `dob`=? 
    WHERE `id`=? LIMIT 1";
    $values=[$frm_data['name'],$frm_data['address'],$frm_data['phonenum'],
        $frm_data['pincode'],$frm_data['dob'],$_SESSION['managerId']];

    if(update($query,$values,'ssssss')){
        $_SESSION['uName'] = $frm_data['name'];
        echo 1;
    }
    else{
        echo 0;
    }
}

if (isset($_POST['profile_form'])) 
{
    session_start();

    $img = uploadUserImage($_FILES['profile']);

    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }


    $u_exist = select("SELECT `profile` FROM `user_cred` WHERE `id`!=? LIMIT 1",[$_SESSION['managerId']],"s");
    $u_fetch = mysqli_fetch_assoc($u_exist);

    deleteImage($u_fetch['profile'],USERS_FOLDER);


    $query = "UPDATE `user_cred` SET `profile`=? WHERE `id`=? LIMIT 1";
    $values=[$img,$_SESSION['managerId']];

    if(update($query,$values,'ss')){
        $_SESSION['uPic'] = $img;
        echo 1;
    }
    else{
        echo 0;
    }
}

if (isset($_POST['pass_form'])) 
{
    $frm_data=filteration($_POST);
    session_start();

    if($frm_data['new_pass']!=$frm_data['confirm_pass']){
        echo 'mismatch';
        exit;
    }
    
    $enc_pass = password_hash($frm_data['new_pass'],PASSWORD_BCRYPT);
 
    $query = "UPDATE `user_cred` SET `password`=? WHERE `id`=? LIMIT 1";
    $values=[$enc_pass,$_SESSION['managerId']];

    if(update($query,$values,'ss')){
        echo 1;
    }
    else{
        echo 0;
    }
}
