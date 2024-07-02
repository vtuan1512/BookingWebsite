<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Recommend</title>
    <style>
        .pop:hover {
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }

        .image-container {
            width: 100%;
            height: auto;
            overflow: hidden;
        }

        .image-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    </style>


</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold text-center">BEST DESTINATION IN DALAT</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
        Da Lat is the capital of Lam Dong province. With an altitude of 1,500 m above sea level. Dalat's weather is cool, making it an ideal resort in the southern region.<br> 
 Once famous for attractions such as the Valley of Love, Lake of Sighs, Two Tomb Pine Hill, Elephant Waterfall...<br>
 Current main attractions when traveling to Da Lat include Xuan Huong Lake, Langbiang Peak, <br> Bao Dai Palace, Tran Le Xuan Villa, Truc Lam Zen Monastery, Tuyen Lam Lake, Da Lat Railway Station (Station Trai Mat)… <br>
 Only about 300km from Saigon, Da Lat city is a wonderful resort. Helps tourists escape the sultry heat of the Southern Delta.
        </p>
    </div>
    <div class="container">
        <div class="row">
            <?php
            $res = selectAll('destination');
            $path = RCM_POST_IMG_PATH;

            while ($row = mysqli_fetch_assoc($res)) {
                echo <<<data
                        <div class="col-lg-4 col-md-6 mb-5 px-4">
                            <div class="bg-white rounded shadow border-top border-4 border-dark pop position-relative">
                                <div class="d-flex flex-column align-items-center mb-2">
                                    <div style="width: 100%; height: 200px; overflow: hidden;">
                                        <img src="$path$row[image]" style="object-fit: cover; width: 100%; height: 100%;">
                                        <a href="destination_blog.php?id=$row[id]" class="text-decoration-none"></a>
                                    </div>
                                    <h5 class="position-absolute text-white bottom-0 start-50 translate-middle-x px-3" style="white-space: nowrap;">$row[name]</h5>
                                </div>
                            </div>
                        </div>

                    data;
            }
            ?>

        </div>
    </div>
    <div class="my-5 px-4">
        <h2 class="fw-bold  text-center">SIGNATURE FOOD IN DALAT</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
        Traveling to Da Lat is an opportunity for you to enjoy delicious specialties including wine and wine, fruits (persimmon, persimmon, persimmon eggs; avocado, peach, strawberry, mulberry,...), <br>
 jams, Bao Loc tea, artichoke tea, and fruits and vegetables (cabbage, Chinese cabbage, spinach, cauliflower, artichokes, celery, peas, carrots, potatoes, chayote, bok choy )… and countless other dishes
 other delicious and attractive food.
        </p>
    </div>
    <div class="container">
        <div class="row">
            <?php
            $res = selectAll('food');
            $path = FOOD_IMG_PATH;

            while ($row = mysqli_fetch_assoc($res)) {
                echo <<<data
                    <div class="col-lg-4 col-md-6 mb-5 px-4">
                            <div class="bg-white rounded shadow border-top border-4 border-dark pop position-relative">
                                <div class="d-flex flex-column align-items-center mb-2">
                                    <div style="width: 100%; height: 200px; overflow: hidden;">
                                        <img src="$path$row[image]" style="object-fit: cover; width: 100%; height: 100%;">
                                        <a href="food_blog.php?id=$row[id]" class="text-decoration-none"></a>
                                    </div>
                                    <h5 class="position-absolute text-white bottom-0 start-50 translate-middle-x px-3" style="white-space: nowrap;">$row[name]</h5>
                                </div>
                            </div>
                    </div>

                    data;
            }
            ?>

        </div>
    </div>

    <?php require('inc/footer.php'); ?>
    <script>
        document.querySelectorAll('.bg-white').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                window.location.href = item.querySelector('a').getAttribute('href');
            });
        });
    </script>


</body>

</html>