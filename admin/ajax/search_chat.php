<?php

    session_start();
    require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
    require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');

    $outgoing_id = $_SESSION['adminId'];

    $searchTerm = mysqli_real_escape_string($con,$_POST['searchTerm']);
    $sql = "SELECT * FROM user_cred WHERE NOT id = {$outgoing_id} AND NOT role = '0' AND
    (name LIKE '%{$searchTerm}%')";

    $output="";
    $query = mysqli_query($con,$sql);
    if(mysqli_num_rows($query) > 0){
        require "/Xampp/xampp/htdocs/BookingWebsite/data_manager.php";
    }else{
        $output .= "No user found!";
    }

    echo $output;

?>