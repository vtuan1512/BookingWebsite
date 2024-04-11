<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');

session_start();
session_destroy();
redirect('adminindex.php');

?>