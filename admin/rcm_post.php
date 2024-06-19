<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');
adminLogin()


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Destination and Food Post</title>
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

    <?php require('../admin/inc/header.php') ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Recommend Post</h3>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="card-title m-0">DESTINATION<h5>
                                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#post-s">
                                                <i class="bi bi-plus-square"></i>Add 
                                            </button>
                                </div>
                                <div class="table-responsive-md" style="height: 650px; overflow-y:scroll;">
                                    <table class="table table-hover border">
                                        <thead>
                                            <tr class="bg-dark text-light">
                                                <th scope="col">#</th>
                                                <th scope="col">Icon</th>
                                                <th scope="col">Name</th>
                                                <th scope="col" width="40%">Description</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="posts-data">
        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">FOOD POST<h5>
                                    <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#food-s">
                                        <i class="bi bi-plus-square"></i>Add
                                    </button>
                        </div>
                        <div class="table-responsive-md" style="height: 650px; overflow-y:scroll;">
                            <table class="table table-hover border">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Icon</th>
                                        <th scope="col">Name</th>
                                        <th scope="col" width="40%">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="foods-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Food post modal -->
    <div class="modal fade" id="food-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="food_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Food</h1>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="food_name" class="form-control shadow-none" required>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Image</label>
                                <input type="file" name="food_icon" id="member_picture_inp" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control shadow-none mb-3" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea id="food_desc" class="form-control shadow-none" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Destination post modal -->
    <div class="modal fade" id="post-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="post_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Destination </h1>
                    </div>
                    <div class="modal-body modal-lg">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="post_name" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Image</label>
                            <input type="file" name="post_icon" id="member_picture_inp" accept="image/jpg, image/jpeg, image/png, image/webp" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="post_desc" class="form-control shadow-none" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="/tinymce_7.1.2/tinymce/js/tinymce/tinymce.min.js"></script>
    <?php require('../admin/inc/scripts.php') ?>
    <script src="/admin/scripts/rcm_post.js"></script>
    <script>
        tinymce.init({
            selector: '#food_desc, #post_desc',
            width: '100%',
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'codesample'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons',
            menu: {
                favs: {
                    title: 'menu',
                    items: 'code visualaid | searchreplace | emoticons'
                }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}'
        });

        // Initialize TinyMCE when the modal is shown
        $('#hotel-s').on('shown.bs.modal', function () {
            tinymce.get('hotel_desc').remove(); // Remove existing instance if any
            tinymce.init({
                selector: '#hotel_desc',
                plugins: [
                    'advlist autolink link image lists charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                    'save table contextmenu directionality emoticons template paste textcolor'
                ],
                toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
                    'bullist numlist outdent indent | link image | print preview media fullpage | ' +
                    'forecolor backcolor emoticons',
                menubar: false,
                statusbar: false,
                height: 300
            });
        });
    </script>


</body>

</html>