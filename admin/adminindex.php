<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <?php require('../admin/inc/links.php'); ?>
</head>
<style>
    div.login-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
    }

    .image-container {
        position: relative;
        width: 100%;
    }

    .image-container img {
        width: 100%;
        height: auto;
    }

    .overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
    }

    .overlay h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .overlay form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .overlay input {
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        width: 200px;
    }

    .overlay button {
        padding: 0.5rem 1rem;
        background-color: purple;
        color: white;
        border: none;
        cursor: pointer;
    }

</style>

<body class="bg-light">

    <div class="image-container">
        <img src="/images/swiper/IMG_23871.png" alt="Client Portal Login">
        
    </div>
    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="POST">
            <h4 class="bg-dark text-white py-3"> LOGIN </h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="email" required type="text" class="form-control shadow-none text-center" placeholder="Please enter your Email">
                </div>
                <div class="mb-4">
                    <input name="password" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                </div>
                <button name="login" type="submit" class="btn text-white custom-bg shadow-none">Login</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $frm_data = filteration($_POST);
        $query = "SELECT * FROM `user_cred` WHERE `email`=? AND `password`=?";
        $value = [$frm_data['email'], $frm_data['password']];
        $result = select($query, $value, 'ss');
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['role'] == 0) {
                $_SESSION['adminLogin'] = true;
                $_SESSION['adminId'] = $row['id'];

                redirect('dashboard.php');
                exit();
            } elseif ($row['role'] == 1) {
                $manager_id = $row['id'];
                $query_hotel = "SELECT hotel_id FROM manager_hotel WHERE manage_id=?";
                $value_hotel = [$manager_id];
                $result_hotel = select($query_hotel, $value_hotel, 'i');
                if ($result_hotel && mysqli_num_rows($result_hotel) > 0) {
                    $row_hotel = mysqli_fetch_assoc($result_hotel);
                    $hotel_id = $row_hotel['hotel_id'];
                    $_SESSION['managerLogin'] = true;
                    $_SESSION['managerId'] = $manager_id;
                    $_SESSION['hotelId'] = $hotel_id;
                    redirect('dashboard_manager.php');
                    exit();
                } else {
                    alert('error', 'No hotel assigned to this manager!');
                }
            } else {
                alert('error', 'Login Failed!');
            }
        } else {
            alert('error', 'Login Failed!');
        }
    }

    ?>



    <?php require('../admin/inc/scripts.php'); ?>
</body>

</html>