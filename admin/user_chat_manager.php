<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
// adminLogin();
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Admin Chat</title>
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

    <?php require('../admin/inc/header_manager.php') ?>

    <div class="container">
        <div class="wrapper">
            <section class="users">
                <header>
                    <div class="content">

                        <?php
                        
                        $manager_id = $_SESSION['managerId'];
                        $sql = mysqli_query($con, "SELECT * FROM `user_cred` WHERE `id`={$manager_id}");
                        $path = USERS_IMG_PATH;
                        if (mysqli_num_rows($sql) > 0) {
                            $row = mysqli_fetch_assoc($sql);
                        }
                        ?>

                        <img src="<?php echo $path . $row['profile'] ?>" alt="User Avatar" class="avatar">

                        <div class="details">
                            <span><?php echo $row['name'] ?></span>

                            <p><?php echo $row['status_chat'] ?></p>

                        </div>
                    </div>

                </header>
                <div class="search">
                    <span class="text">Select an user to start chat</span>
                    <input type="text" placeholder="Enter name to search ...">
                    <button><i class="fas fas-search"></i></button>
                </div>
                <div class="users-list">

                </div>

            </section>
        </div>
    </div>
    
    <?php require('../admin/inc/scripts.php') ?>
    <script type="text/javascript" src="/admin/scripts/users_chat_manager.js"></script> <!-- Adjust the path if necessary -->



</body>

</html>