<?php
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/db_config.php');
require('/Xampp/xampp/htdocs/BookingWebsite/admin/inc/essentials.php');



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Voucher</title>
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

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4"> Voucher</h3>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="card-title m-0">VOUCHER<h5>
                                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#voucher-s">
                                                <i class="bi bi-plus-square"></i>Add
                                            </button>
                                </div>
                                <div class="table-responsive-md" style="height: 650px; overflow-y:scroll;">
                                    <table class="table table-hover border">
                                        <thead>
                                            <tr class="bg-dark text-light">
                                                <th scope="col">#</th>
                                                <th scope="col" class="text-center">Voucher-code</th>
                                                <th scope="col" class="text-center">Value</th>
                                                <th scope="col" class="text-center">Type</th>
                                                <th scope="col" class="text-center">Min-value</th>
                                                <th scope="col" class="text-center">Quantity</th>
                                                <th scope="col" class="text-center">From</th>
                                                <th scope="col" class="text-center">To</th>
                                                <th scope="col" class="text-center">Description</th>
                                                <th scope="col" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vouchers-data">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- add modal -->
    <div class="modal fade" id="voucher-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="voucher_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Voucher</h1>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Voucher-code</label>
                            <input type="text" name="voucher_code" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Voucher-value</label>
                            <input type="number" name="voucher_value" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Voucher-type</label>
                            <input type="text" name="voucher_type" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Booking-min-value</label>
                            <input type="number" name="booking_min_value" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Quantity</label>
                            <input type="number" name="quantity" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">From(date)</label>
                            <input type="date" name="from_date" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">To(date)</label>
                            <input type="date" name="to_date" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="voucher_desc" class="form-control shadow-none" rows="4"></textarea>
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
    <!-- edit -->
    <div class="modal fade" id="edit-voucher" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="voucher_edit_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title">Add Voucher</h1>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Voucher-code</label>
                            <input type="text" name="voucher_code" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Voucher-value</label>
                            <input type="number" name="voucher_value" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Voucher-type</label>
                            <input type="text" name="voucher_type" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Booking-min-value</label>
                            <input type="number" name="booking_min_value" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Quantity</label>
                            <input type="number" name="quantity" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">From(date)</label>
                            <input type="date" name="from_date" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">To(date)</label>
                            <input type="date" name="to_date" class="form-control shadow-none mb-3" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="voucher_desc" class="form-control shadow-none" rows="4"></textarea>
                        </div>
                        <input type="hidden" name="voucher_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <?php require('../admin/inc/scripts.php') ?>
    <script src="/admin/scripts/voucher_manager.js"></script>



</body>

</html>