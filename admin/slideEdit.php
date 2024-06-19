<?php

require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');

adminLogin()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Slide_Edit</title>
    <?php require('../admin/inc/links.php') ?>
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
    </style>
</head>


<body class="bg-light">

    <?php require('../admin/inc/header.php') ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Slide Edit</h3>
                <!-- Slide sectiom -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Image<h5>
                                    <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#carousel-s">
                                        <i class="bi bi-plus-square"></i>Add 
                                    </button>
                        </div>
                        <div class="row" id="carousel-data">
                        </div>
                    </div>
                </div>
                <!-- Slide modal -->
                <div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="carousel_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title">Add Image</h1>
                                </div>
                                <div class="modal-body">
                                  
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Pictures</label>
                                        <input type="file" name="carousel_picture" id="carousel_picture_inp" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control shadow-none" required>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="carousel_picture.value=''" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('../admin/inc/scripts.php') ?>
    <script src="/admin/scripts/carousel.js"></script>
    
    
</body>

</html>