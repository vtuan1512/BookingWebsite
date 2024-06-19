
let hotel_s_form = document.getElementById('hotel_s_form');

hotel_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_hotel();
});

function add_hotel() {
    let data = new FormData();
    data.append('name', hotel_s_form.elements['hotel_name'].value);
    data.append('image', hotel_s_form.elements['hotel_image'].files[0]);
    data.append('address', hotel_s_form.elements['hotel_address'].value);
    data.append('phone', hotel_s_form.elements['hotel_phone'].value);
    data.append('bank', hotel_s_form.elements['hotel_bank'].value);
    data.append('bank_no', hotel_s_form.elements['hotel_bank_no'].value);
    data.append('email', hotel_s_form.elements['hotel_email'].value);
    data.append('rating', hotel_s_form.elements['hotel_rating'].value);
    data.append('desc', hotel_s_form.elements['hotel_desc'].value);
    data.append('add_hotel', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/hotel.php', true);
    xhr.onload = function () {
        var myModal = document.getElementById('hotel-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if (this.responseText == 'inv-img') {
            alert('error', 'Only PNG, JPEG, WEBP format allowed!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Image upload failed, it should be less than 1MB!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image upload failed. Server Down!');
        } else {
            alert('success', 'New hotel added!');
            hotel_s_form.reset();
            get_hotels();
        }
    }
    xhr.send(data);
}

function get_hotels() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/hotel.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        document.getElementById('hotels-data').innerHTML = this.responseText;
    }
    xhr.send('get_hotels');
}

function rem_hotel(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/hotel.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Hotel removed!');
            get_hotels();
        } else {
            alert('error', 'Server down!');
        }
    }
    xhr.send('rem_hotel=' + val);
}
function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/hotel.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {

        if (this.responseText == 1) {
            alert('success', 'Status Changed !');
            get_hotels();
        } else {
            alert('error', 'Server Down!');
        }
    }

    xhr.send('toggle_status=' + id + '&value=' + val);

}

let edit_hotel_form = document.getElementById('edit_hotel_form');

function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/hotel.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        let data = JSON.parse(this.responseText);

        edit_hotel_form.elements['name'].value = data.hoteldata.name;
        edit_hotel_form.elements['image'].value = data.hoteldata.image;
        edit_hotel_form.elements['phonenum'].value = data.hoteldata.phone;
        edit_hotel_form.elements['email'].value = data.hoteldata.email;
        edit_hotel_form.elements['rating'].value = data.hoteldata.rating;
        edit_hotel_form.elements['bank'].value = data.hoteldata.bank_account;
        edit_hotel_form.elements['bank_no'].value = data.hoteldata.bank;
        edit_hotel_form.elements['address'].value = data.hoteldata.children;
        edit_hotel_form.elements['desc'].value = data.hoteldata.description;

    }

    xhr.send('get_hotel=' + id);
}

edit_hotel_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_hotel();
});

function submit_edit_hotel() {
    let data = new FormData();
    data.append('edit_hotel', '');
    data.append('image', edit_hotel_form.elements['image'].value);
    data.append('name', edit_hotel_form.elements['name'].value);
    data.append('phonenum', edit_hotel_form.elements['phonenum'].value);
    data.append('email', edit_hotel_form.elements['email'].value);
    data.append('hotel_id', edit_hotel_form.elements['hotel_id'].value);
    data.append('rating', edit_hotel_form.elements['rating'].value);
    data.append('bank', edit_hotel_form.elements['bank'].value);
    data.append('bank_no', edit_hotel_form.elements['bank_no'].value);
    data.append('address', edit_hotel_form.elements['address'].value);
    data.append('desc', edit_hotel_form.elements['desc'].value);


    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/hotel.php', true);
    xhr.onload = function () {
        var myModal = document.getElementById('edit-hotel');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if (this.responseText == 1) {
            alert('success', 'Room data edited!');
            edit_hotel_form.reset();
            get_hotels();
        } else {
            alert('error', 'Server Down!');
        }
    }

    xhr.send(data);
}


window.onload = function () {
    get_hotels();
}

