<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Recommend</title>

</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold text-center">SPECIAL VOUCHER</h2>
        <div class="h-line bg-dark" style="width: 50%;"></div>

    </div>
    <div class="container">
        <div class="row">
            <?php
            $sql = "SELECT * FROM `voucher` WHERE `hotel_id` = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('i', $hotel_id); 
            $hotel_id = 0;
            $stmt->execute();

            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $from_date_timestamp = strtotime($row['from_date']);
                    $to_date_timestamp = strtotime($row['to_date']);
                    $current_timestamp = time();

                    if ($current_timestamp >= $from_date_timestamp && $current_timestamp <= $to_date_timestamp) {
                        echo <<<data
                            <div class="col-lg-4">
                                <div class="card mb-3" style="max-width: 540px;">
                                    <div class="row g-0">
                                        <div class="col-md-4"">
                                            <img src="/images/logo.png" class="img-fluid rounded-start">
                                        </div>
                                        <div class="col-md-8" >
                                            <div class="card-body d-flex flex-column">
                                                <h5 class="card-title text-success">Discount $row[voucher_value] $row[voucher_type]</h5>
                                                <p class="card-text"><small class="text-body-secondary">$row[description]</small></p>
                                                <p class="card-text"><small class="text-body-secondary">Quantiy:$row[quantity] </small></p>
                                                <p class="card-text"><small class="text-body-secondary">From $row[from_date] to $row[to_date]</small></p>
                                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                                    <div class="mb-3 bg-white voucher">
                                                        <a href="" class="text-decoration-none text-dark" >Lay ma: $row[voucher_code]</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        data;
                    }
                }
            }
            ?>


        </div>
    </div>


    <?php require('inc/footer.php'); ?>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ClipboardJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var vouchers = document.querySelectorAll('.voucher');
            vouchers.forEach(function(voucher) {
                voucher.addEventListener('click', function() {
                    var voucherCode = this.querySelector('a').innerText.split(':')[1].trim();
                    var tempInput = document.createElement('input');
                    tempInput.value = voucherCode;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);
                    alert('success', 'Copy code success !');
                });
            });
        });
    </script>
</body>

</html>