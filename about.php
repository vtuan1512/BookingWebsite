<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - About</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/bootstrap-5.3.3/dist/css/bootstrap.min.css">
    <style>
        .box {
            border-top-color: var(--teal) !important;
        }
    </style>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold  text-center">Da Lat Booking - Complete journey, lifelong joy.</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit, amet consectetur adipisicing elit.
            Ut veritatis odit adipisci amet, saepe explicabo inventore
            quis possimus, <br>deserunt fugit nulla facilis maiores repellat
            culpa atque, sapiente assumenda! Voluptatem, dolor!
        </p>
    </div>
    <div class="container">
        <div class="row justify-content-between aign-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3"> Welcome to Da Lat Booking, the leading reputable hotel booking platform in Da Lat. </h3>
                <p>
                 We help you discover and book the best hotels, resorts, and homestays in the city of flowers. With a rich hotel portfolio ranging from 5-star luxury to cozy budget accommodations, Da Lat Booking is committed to bringing you memorable accommodation experiences.<br><br>

Not only stopping at providing accommodation, Da Lat Booking also accompanies you in discovering interesting activities and famous attractions in Da Lat. Whether you want to enjoy a morning stroll around Xuan Huong Lake, visit the majestic Prenn Waterfall, or simply enjoy a cup of coffee at a small roadside shop, we are ready to assist you.<br><br>

With a friendly and easy-to-use interface, you can easily search, compare prices and book with just a few clicks. Our customer care team is available 24/7, ready to answer all your questions in many different languages. Furthermore, with dozens of flexible payment methods, Da Lat Booking helps you easily complete transactions quickly and safely.<br><br>

Da Lat Booking has been trusted and chosen by millions of users for their vacation in Da Lat. So what are you waiting for? Start your travel plans today. Whether you are looking for a romantic vacation, a family trip, or an adventure of discovery, Da Lat Booking is ready to accompany you every step of the way. Just one click, you will find everything you want.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/about/about.jpg" class="w-100">
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center">
                    <img src="images/about/hotel.svg" width="70px">
                    <h4 class="mt-3"> Hotels & Resorts </h4>
                </div>

            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center">
                    <img src="images/about/customers.svg" width="70px">
                    <h4 class="mt-3">150+ Customers </h4>
                </div>

            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center">
                    <img src="images/about/rating.svg" width="70px">
                    <h4 class="mt-3"> +100 Reviews</h4>
                </div>

            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center">
                    <img src="images/about/staff.svg" width="70px">
                    <h4 class="mt-3"> 200+ Staff </h4>
                </div>

            </div>
        </div>
    </div>

    <h3 class="my-5 fw-bold text-center">Mangement Team</h3>
    <div class="container px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                <?php 
                    $about_r = selectAll('team_details');
                    $path = ABOUT_IMG_PATH;
                    while($row = mysqli_fetch_assoc($about_r)){
                        echo<<<data
                        <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                            <img src="$path$row[picture]" class="w-100">
                            <h5 class="mt-2">$row[name]</h5>
                        </div>
                        data;
                    }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoint: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },

            }
        });
    </script>

</body>

</html>