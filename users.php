

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> Chat </title>

</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

   

    <div class="container">
        <div class="wrapper">
            <section class="users">
                <header>
                    <div class="content">

                        <?php
                        $sql = mysqli_query($con, "SELECT * FROM `user_cred` WHERE `id`={$_SESSION['uId']}");
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


    <?php require('inc/footer.php'); ?>

    <script type="text/javascript" src="/users.js"></script> <!-- Adjust the path if necessary -->
</body>

</html>