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
        Đà Lạt là thủ phủ của tỉnh Lâm Đồng. Với độ cao 1.500 m trên mặt nước biển. tiết trời Đà Lạt mát lạnh, là nơi nghỉ dưỡng lý tưởng ở khu vực miền Nam. <br>
        Từng một thời nổi tiếng với các điểm tham quan như Thung Lũng Tình Yêu, Hồ Than Thở, Đồi Thông Hai Mộ, Thác Voi… 
        Các điểm tham quan chính hiện nay<br> khi du lịch Đà Lạt gồm có Hồ Xuân Hương, Đỉnh Langbiang, Dinh Bảo Đại, Biệt Điện Trần Lệ Xuân, Thiền Viện Trúc Lâm, Hồ Tuyền Lâm, Nhà Ga Đà Lạt (Nhà ga Trại Mát)… <br>
        Chỉ cách Sài Gòn chừng 300km, thành phố Đà Lạt là nơi nghỉ dưỡng tuyệt vời. Giúp du khách thoát khỏi cái oi bức nóng nực của vùng đồng bằng Nam Bộ.
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
        Du lịch Đà Lạt là dịp để bạn thưởng thức những đặc sản thơm ngon gồm rượu vang và rượu cần, các loại trái cây (hồng tàu, hồng khía, hồng trứng; bơ, đào long, dâu tây, dâu tằm,…), <br>
        các loại mứt, trà Bảo Lộc, trà Atiso, và các loại rau củ quả (cải bắp, cải thảo, bó xôi, súp lơ, Atiso, cần tây, đậu Hà Lan, cà rốt, khoai tây, su su, cải ngọt)… và vô vàn những món 
        ăn thơm ngon hấp dẫn khác.
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