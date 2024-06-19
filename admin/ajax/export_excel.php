<?php

require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');

if (isset($_POST['export_excel'])) {
    $sql = "
    SELECT bo.*, bd.*
    FROM booking_order bo
    JOIN booking_detail bd ON bo.booking_id = bd.booking_id
    ";
  $result = mysqli_query($con, $sql);

  $finaldata = array();
  while ($data = mysqli_fetch_assoc($result)) {
    $data['date_time'] = date('Y-m-d H:i:s', strtotime($data['date_time']));
    $finaldata[] = $data;
  }

  $filename = "Booking_order_" . date('Ymdhis') . ".xls";
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=\"$filename\"");
  $firstrow = false;
  foreach ($finaldata as $data) {
    if (!$firstrow) {
      echo implode("\t", array_keys($data)) . "\n";
      $firstrow = true;
    }
    echo implode("\t", array_values($data)) . "\n";
  }
  exit;
}
?>
