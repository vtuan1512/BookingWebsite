<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
// session_start();
// if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
//     redirect('adminindex.php');
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <?php require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/links.php'); ?>
</head>
<style>
    div.login-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
    }
</style>

<body class="bg-light">
    <h1 class="text-center mt-5 " style="font-size: 50px; color:#3c97d6;">Login to Admin Dashboard</h1>
    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="POST">
            <h4 class="bg-dark text-white py-3"> LOGIN </h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="email" required type="text" class="form-control shadow-none text-center" placeholder="Admin Email">
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
        $query = "SELECT * FROM `account_manager` WHERE `email`=? AND `password`=?";
        $value = [$frm_data['email'], $frm_data['password']];
        $result = select($query, $value, 'ss');
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if ($row['role'] == 0 || $row['role'] == 1) {
                $_SESSION['adminLogin'] = true;
                $_SESSION['adminId'] = $row['account_number'];
                redirect('dashboard.php');
                exit();
            } else {
                alert('error', 'Login Failed !');
            }
        } else {
            alert('error', 'Login Failed !');
        }
    }
    ?>



    <?php require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/scripts.php'); ?>
</body>

</html>