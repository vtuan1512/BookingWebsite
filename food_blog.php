<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Blog Details</title>

</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <?php
    if (!isset($_GET['id'])) {
        redirect('recommend.php');
    }

    $data = filteration($_GET);

    $desti_res = select("SELECT * FROM `food` WHERE `id`=?", [$data['id']], 'i');

    if (mysqli_num_rows($desti_res) == 0) {
        redirect('recommend.php');
    }
    $desti_data = mysqli_fetch_assoc($desti_res);
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold"><?php echo $desti_data['name'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="recommend.php" class="text-secondary text-decoration-none">Blogs</a>
                </div>
            </div>
            <div class="col-lg-7 col-md-12 px-4">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $destination_img = $desti_data['image']; 
                        echo "
                            <div class='carousel-item active'>
                                <img src='" . FOOD_IMG_PATH . $destination_img . "' class='d-block w-100'>
                            </div>
                        ";
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <h4 class="fw-bold text-center mt-3">Description</h4>
                    <div class="card-body">
                        <?php echo $desti_data['description']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

</body>

</html>
