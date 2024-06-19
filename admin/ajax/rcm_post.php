<?php

require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
// adminLogin();

if (isset($_POST['add_food'])) {
    $frm_data = filteration($_POST);
    $img_r = uploadImage($_FILES['image'], RCM_FOOD_FOLDER);

    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO `food`(`image`, `name`, `description`) VALUES (?,?,?)";
        $values = [$img_r , $frm_data['name'],$frm_data['desc']];
        $result = insert($q, $values, 'sss');
        echo $result;
    }
}
if (isset($_POST['get_foods'])) {
    $res = selectAll('food');
    $i = 1;
    $path = FOOD_IMG_PATH;

    while ($row = mysqli_fetch_assoc($res)) {
        echo <<<data
        <tr class="align-middle">
            <td>$i</i>
            <td><img src = "$path$row[image]" width="110px"></td>
            <td>$row[name]</td>
            <td>$row[description]</td>
            <td>
                <button type="button" onclick=" rem_food($row[id])" class="btn btn-danger btn-sm shadow-none">
                    <i class="bi bi-trash3"></i>Delete
                </button>
            </td>
        </tr>
        data;
        $i++;
    }
}

if (isset($_POST['rem_food'])) {
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_food']];

    $q = "DELETE FROM `food` WHERE `id`=?";
    $res = delete($q, $values, 'i');
    echo $res;


}

if (isset($_POST['add_post'])) {
    $frm_data = filteration($_POST);
    $img_r = uploadImage($_FILES['image'], RCM_POST_FOLDER);

    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO `destination`(`image`, `name`, `description`) VALUES (?,?,?)";
        $values = [$img_r , $frm_data['name'],$frm_data['desc']];
        $result = insert($q, $values, 'sss');
        echo $result;
    }
}

if (isset($_POST['get_posts'])) {
    $res = selectAll('destination');
    $i = 1;
    $path = RCM_POST_IMG_PATH;

    while ($row = mysqli_fetch_assoc($res)) {
        echo <<<data
        <tr class="align-middle">
            <td>$i</i>
            <td><img src = "$path$row[image]" width="110px"></td>
            <td>$row[name]</td>
            <td>$row[description]</td>
            <td>
                <button type="button" onclick=" rem_post($row[id])" class="btn btn-danger btn-sm shadow-none">
                    <i class="bi bi-trash3"></i>Delete
                </button>
            </td>
        </tr>
        data;
        $i++;
    }
}

if (isset($_POST['rem_post'])) {
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_post']];

    $q = "DELETE FROM `destination` WHERE `id`=?";
    $res = delete($q, $values, 'i');
    echo $res;
} 
