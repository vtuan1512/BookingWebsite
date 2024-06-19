<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Bookings</title>

</head>
<style>
    img.course_qr_img {
        width: 477px;
    }

    .custom-alert {
        position: fixed;
        top: 80px;
        right: 25px;
        z-index: 11111;

    }
</style>

<body class="bg-light">
    <?php require('inc/header.php');
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">MY WISHLISTS</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="wishlists.php" class="text-secondary text-decoration-none">WISHLISTS</a>
                </div>
                <div id="wishlists-data">
                    
                </div>
            </div>
        </div>
    </div>

    <?php
    require('inc/footer.php');
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            get_wishlist();

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('delete-wishlist-btn')) {
                    var hotelId = event.target.getAttribute('data-hotel-id');
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'ajax/delete_wishlists.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var response = xhr.responseText;
                            if (response === 'failed') {
                                alert('error', 'Failed to delete from wishlist.');
                            } else {
                                alert('success', 'Delete hotel from wishlists successful!')
                                get_wishlist();
                            }
                        } else {
                            alert('Server Error: ' + xhr.status);
                        }
                    };
                    xhr.send('hotelId=' + hotelId);
                }
            });
        });

        function get_wishlist() {
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/delete_wishlists.php', true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                document.getElementById('wishlists-data').innerHTML = this.responseText;
            }
            xhr.send('get_wishlist');
        }
    </script>






</body>

</html>