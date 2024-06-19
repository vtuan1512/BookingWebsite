


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

    <?php
    if(!isset($_SESSION['uId'])){
        header("loction: login.php");
    }
    ?>

    <div class="container">
    <div class="wrapper">
        <section class="chat-area">
            <header>
                <?php 
                    $user_id = mysqli_real_escape_string($con,$_GET['user_id']);
                    $path = USERS_IMG_PATH;
                    $sql = mysqli_query($con,"SELECT * FROM `user_cred` WHERE `id`={$user_id}");
                    if(mysqli_num_rows($sql) > 0){
                        $row = mysqli_fetch_assoc($sql);

                    }else{
                        header("location: users.php");
                    }
                ?>

                <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                <img src="<?php echo $path.$row['profile'] ?>" alt="">
                <div class="details">
                    <span><?php echo $row['name']?></span>
                    <p><?php echo $row['status_chat']?></p>
                </div>
            </header>
            <div class="chat-box">
            </div>
            <form  class="typing-area" method="post">
                <input type="text" class="incoming_id" name="incoming_id" value="<?php echo $user_id ?>" hidden>
                <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off"></input>
                <button><i class="fab fa-telegram-plane"></i></button>
            </form>
        </section>
    </div>
    </div>


    <?php require('inc/footer.php'); ?>

    <script src="/chat.js"></script>
</body>

</html>