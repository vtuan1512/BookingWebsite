
function get_bookings(search='',page =1 ) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/booking_records_manager.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        document.getElementById('table-data').innerHTML = this.responseText;
    }

    xhr.send('get_bookings&search='+search+'&page='+page);

}


window.onload = function () {
    get_bookings();
}