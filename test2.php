<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Rooms</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/common.css">
</head>

<body>

    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">Our Rooms</h2>
    <div class="container">
        <div class="row" id="roomList">

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-gp7CEkEW0rjXxUC+YXz7E8lVC5t/DByMHbSlGxhoKdR8Sta+Nqc1HwMHkcL+DdjP" crossorigin="anonymous"></script>
    <script>
        fetch('http://localhost:3030/room')
            .then(response => response.json())
            .then(data => {
                const roomList = document.getElementById('roomList');
                data.forEach(room => {
                    const card = document.createElement('div');
                    card.className = 'col-lg-4 col-md-6 my-3';
                    card.innerHTML = `
                    <div class="card border-0 shadow" style="max-width: 350px;margin:auto;">
                        <img src="${room.imageURl}" class="card-img-top">
                        <div class="card-body">
                            <h5>${room.name}</h5>
                            <h6 class="mb-4">${room.price} per night</h6>
                            <div class="features mb-4">
                                <h6 class="mb-1">Features</h6>
                                ${room.features.map(feature => `<span class="badge rounded-pill bg-light text-dark text-wrap">${feature}</span>`).join('')}
                             </div>
                            <div class="facilities mb-4">
                                <h6 class="mb-1">Facilities</h6>
                                ${room.facilities.map(facility => `<span class="badge rounded-pill bg-light text-dark text-wrap">${facility}</span>`).join('')}
                            </div>
                            <div class="guests mb-4">
                                <h6 class="mb-1">Guest</h6>
                                <span class="badge rounded-pill bg-light text-dark text-wrap">${room.adult} Adults</span>
                                <span class="badge rounded-pill bg-light text-dark text-wrap">${room.children} Children</span>
                            </div>
                            <div class="rating mb-4">
                                <h6 mb-1>Rating</h6>
                                <span class="badge rounded-pill bg-light">
                                    ${'⭐'.repeat(Math.floor(room.rating))}
                                </span>
                            </div>
                            <div class="d-flex justify-content-evenly mb-2">
                                <a href="#" class="btn btn-sm text-white custom-bg shadow-none">Book Now</a>
                                <a href="#" class="btn btn-sm btn-outline-dark shadow-none">More Details</a>
                            </div>
                        </div>
                    </div>
                `;
                    roomList.appendChild(card);
                });
            })
            .catch(error => console.log(error));
    </script>


</body>

</html>