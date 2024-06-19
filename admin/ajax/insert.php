<?php

    session_start();
    if(isset($_SESSION['adminId'])){    
        require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');

        $outgoing_id = $_SESSION['adminId'];
        $incoming_id = mysqli_real_escape_string($con, $_POST['incoming_id']);
        
        $message = mysqli_real_escape_string($con,$_POST['message']);

        if(!empty($message)){
            $sql = mysqli_query($con, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
            VALUES ('$incoming_id', '$outgoing_id', '$message')") or die();
        }
    }
    else{
        header("location: ../login.php");
    }


?>