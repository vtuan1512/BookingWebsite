<?php

require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
// adminLogin();

if (isset($_POST['add_hotel'])) {
    $frm_data = filteration($_POST);
    $img_r = uploadImage($_FILES['image'], HOTEL_FOLDER);

    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO `hotel`(`image`, `name`, `description`,`address`,`phone`,`bank`,`bank_account`,`email`,`rating`) VALUES (?,?,?,?,?,?,?,?,?)";
        $values = [$img_r, $frm_data['name'], $frm_data['desc'], $frm_data['address'], $frm_data['phone'], $frm_data['bank_no'], $frm_data['bank'], $frm_data['email'], $frm_data['rating']];
        $result = insert($q, $values, 'ssssissss');
        echo $result;
    }
}
if (isset($_POST['get_hotels'])) {
    $q = "SELECT * FROM `hotel` WHERE `removed` = 0";
    $res = mysqli_query($con, $q);
    $i = 1;
    $path = HOTEL_IMG_PATH;


    while ($row = mysqli_fetch_assoc($res)) {
        $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";
        if (!$row['status']) {
            $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-danger btn-sm shadow-none'>inactive</button>";
        }
        echo <<<data
        <tr class="align-middle">
            <td>$i</td>
            <td><img src="$path{$row['image']}" width="110px"></td>
            <td>{$row['name']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['email']}</td>
            <td>{$row['rating']}</td>
            <td>{$row['address']}</td>
            <td>{$row['description']}</td>
            <td>$status</td>
            <td>
                <button type="button" onclick="rem_hotel({$row['id']})" class="btn btn-danger shadow-none btn-sm">
                    <i class="bi bi-trash"></i>
                </button>
                <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#hotel-edit'>
                        <i class='bi bi-pencil-square'></i>
                </button
                
            </td>
        </tr>
        data;
        $i++;
    }
}

if (isset($_POST['edit_hotel'])) {
    $q1 = "UPDATE `hotel` SET `name`=?, `image`=?, `phone`=?, `email`=?, `rating`=?, `bank_account`=?, `bank`=?, `address`=?, `description`=? WHERE `id`=?";
    $values = [$_POST['name'], $_POST['image'], $_POST['phonenum'], $_POST['email'], $_POST['rating'], $_POST['bank'], $_POST['bank_no'], $_POST['address'], $_POST['desc'], $_POST['hotel_id']];
    if (update($q1, $values, 'ssssissssi')) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['rem_hotel'])) {
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_hotel']];

    $q = "UPDATE `hotel` SET `removed` = 1 WHERE `id`=?";
    $res = update($q, $values, 'i');
    echo $res;
}

if (isset($_POST['toggle_status'])) {
    $frm_data = filteration(($_POST));
    $q = "UPDATE `hotel` SET `status`=? WHERE `id`=?";
    $v = [$frm_data['value'], $frm_data['toggle_status']];
    if (update($q, $v, 'ii')) {
        echo 1;
    } else {
        echo 0;
    }
}
