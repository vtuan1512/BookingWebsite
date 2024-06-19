<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
// adminLogin();

if (isset($_POST['add_voucher'])) {

    $frm_data = filteration($_POST);
    $from_date = date('Y-m-d', strtotime($frm_data['from_date']));
    $to_date = date('Y-m-d', strtotime($frm_data['to_date']));
    
    $q = "INSERT INTO `voucher`(`voucher_code`, `voucher_value`, `voucher_type`, `booking_min_value`,`quantity`,`from_date`, `to_date`, `description`) VALUES (?,?,?,?,?,?,?,?)";
    $values = [$frm_data['voucher_code'], $frm_data['voucher_value'], $frm_data['voucher_type'], $frm_data['booking_min_value'],$frm_data['quantity'] ,$from_date, $to_date, $frm_data['description']];
    $result = insert($q, $values, 'sisiisss');
    if ($result) {
        echo 'success';
    } else {
        echo 'error';
    }
}


if (isset($_POST['get_vouchers'])) {
    $res = selectAll('voucher');
    $i = 1;

    while ($row = mysqli_fetch_assoc($res)) {
        echo <<<data
        <tr class="align-middle">
            <td>$i</i>
            <td>$row[voucher_code]</td>
            <td>$row[voucher_value]</td>
            <td>$row[voucher_type]</td>
            <td>$row[booking_min_value]</td>
            <td>$row[quantity]</td>
            <td>$row[from_date]</td>
            <td>$row[to_date]</td>
            <td>$row[description]</td>
            <td>
                <button type="button" onclick=" rem_voucher($row[id])" class="btn btn-danger btn-sm shadow-none">
                    <i class="bi bi-trash3"></i>Delete
                </button>
            </td>
        </tr>
        data;
        $i++;
    }
}

if (isset($_POST['rem_voucher'])) {
    $frm_data = filteration($_POST);
    $values = [$frm_data['rem_voucher']];

    $q = "DELETE FROM `voucher` WHERE `id`=?";
    $res = delete($q, $values, 'i');
    echo $res;
}
