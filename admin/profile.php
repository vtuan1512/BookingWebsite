<?php


require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
session_start(); // Start the session

if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
    redirect('adminindex.php');
}

$u_exist = null;
if (isset($_SESSION['adminId'])) {
    $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['adminId']], 'i');
}

if ($u_exist && mysqli_num_rows($u_exist) == 0) {
    redirect('adminindex.php');
}

$u_fetch = mysqli_fetch_assoc($u_exist);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Profile</title>
    <?php require('../admin/inc/links.php') ?>
</head>
<style>
    #dashboard-menu {
        height: 100%;
        position: fixed;
        z-index: 11;
    }

    @media screen and (max-width: 992px) {

        #dashboard-menu {
            height: auto;
            width: 100%;
        }

        #main-content {
            margin-top: 60px;
        }
    }

    tr.bg-dark.text-light th {
        background-color: black;
        color: #ffffff;
    }
</style>

<body class="bg-light">
    <?php
    require('../admin/inc/header.php');

    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">PROFILE</h3>
                <div class="col-12 my-5 mb-4 px-4">
                    <h2 class="fw-bold">PROFILE</h2>
                    <div style="font-size: 14px;">
                        <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                        <span class="text-secondary"> > </span>
                        <a href="rooms.php" class="text-secondary text-decoration-none">PROFILE</a>
                    </div>
                </div>

                <div class="col-12 mb-5 px-4">
                    <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                        <form id="info-form">
                            <h5 class="mb-3 fw-bold">BASIC INFORMATION</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" value="<?php echo $u_fetch['name'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input name="phonenum" value="<?php echo $u_fetch['phonenum'] ?>" type="number" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date of birth</label>
                                    <input name="dob" value="<?php echo $u_fetch['dob'] ?>" type="date" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Pincode</label>
                                    <input name="pincode" value="<?php echo $u_fetch['pincode'] ?>" type="number" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $u_fetch['address'] ?>
                                </textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark shadow-none ">Save Changes</button>
                            <button type="button" class="btn btn-dark shadow-none" id="info-form-reset">Reset</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-4 mb-5 px-4">
                    <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                        <form id="profile-form">
                            <h5 class="mb-3 fw-bold">PICTURE:</h5>
                            <img src="<?php echo USERS_IMG_PATH . $u_fetch['profile'] ?>" class=" img-fluid mb-3">
                            <label class="form-label fw-bold">New Picture:</label>
                            <input name="profile" type="file" accept="image/jpg, image/jpeg, image/png, image/webp" class="mb-4 form-control shadow-none">
                            <button type="submit" class="btn btn-dark shadow-none ">Save Changes</button>
                            <button type="button" class="btn btn-dark shadow-none" id="info-form-reset">Reset</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-8 mb-5 px-4">
                    <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                        <form id="pass-form">
                            <h5 class="mb-3 fw-bold">RESET PASSWORD:</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password</label>
                                    <input name="new_pass" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Confirm Password</label>
                                    <input name="confirm_pass" class="form-control shadow-none" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-dark shadow-none ">Save Changes</button>
                            <button type="button" class="btn btn-dark shadow-none" id="info-form-reset">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?php require('../admin/inc/scripts.php') ?>
    <script src="/admin/scripts/profile.js"></script>
    <script>

    </script>



</body>

</html>