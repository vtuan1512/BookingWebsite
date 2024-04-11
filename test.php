<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="/css/common.css">
<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Truy vấn để lấy thông tin về các khách sạn
$sql = "SELECT * FROM room";
$result = $conn->query($sql);

// Kiểm tra xem có dữ liệu không
if ($result->num_rows > 0) {
    echo '<h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our Rooms</h2>
        <div class="container">
            <div class="row">';
    // Lặp qua các bản ghi và hiển thị thông tin
    while($row = $result->fetch_assoc()) {
        echo '<div class="col-lg-4 col-md-6 my-3">
                <div class="card border-0 shadow" style="max-width: 350px;margin:auto;">
                    <img src='.$row['image'].' class="card-img-top">

                    <div class="card-body">
                        <h5>' . $row['name'] . '</h5>
                        <h6 class="mb-4">' . $row['price'] . ' per night</h6>
                        <div class="features mb-4">
                            <h6 class="mb-1">Features</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">' . $row['features'] . ' </span>
                        </div>
                        <div class="facilities mb-4">
                            <h6 class="mb-1">Facilities</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">' . $row['facilities'] . '</span>
                        </div>
                        <div class="guests mb-4">
                            <h6 class="mb-1">Guest</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">' . $row['adults'] . ' </span>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">' . $row['children'] . ' </span>
                        </div>
                        <div class="rating mb-4">
                            <h6 mb-1>Rating</h6>
                            <span class="badge rounded-pill bg-light text-dark text-wrap">' . $row['rating'] . '</span>
                        </div>
                        <div class="d-flex justify-content-evenly mb-2">
                            <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Book Now</a>
                            <a href="#" class="btn btn-sm btn-outline-dark shadow-none">More Details</a>
                        </div>
                    </div>

                </div>
            </div>';
    }
    echo '</div></div>';
} else {
    echo "Không có dữ liệu về khách sạn";
}
$conn->close();
?>
