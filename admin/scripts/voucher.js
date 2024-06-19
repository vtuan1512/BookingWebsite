
let voucher_s_form = document.getElementById('voucher_s_form');

voucher_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_voucher();
})

function add_voucher() {
    let data = new FormData();
    data.append('voucher_code', voucher_s_form.elements['voucher_code'].value);
    data.append('voucher_value', voucher_s_form.elements['voucher_value'].value);
    data.append('voucher_type', voucher_s_form.elements['voucher_type'].value);
    data.append('booking_min_value', voucher_s_form.elements['booking_min_value'].value);
    data.append('quantity', voucher_s_form.elements['quantity'].value);
    data.append('from_date', voucher_s_form.elements['from_date'].value);
    data.append('to_date', voucher_s_form.elements['to_date'].value);
    data.append('description', voucher_s_form.elements['voucher_desc'].value);


    data.append('add_voucher', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/voucher.php', true);
    xhr.onload = function () {
        var myModal = document.getElementById('voucher-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if (this.responseText == 'success') {
            voucher_s_form.reset(); 
            alert('success','Add voucher success')
            get_vouchers();
        } else {
            alert('error', 'Failed to add voucher.');
        }
    }

    xhr.send(data);
}

function get_vouchers() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/voucher.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        document.getElementById('vouchers-data').innerHTML = this.responseText;

    }
    xhr.send('get_vouchers');
}

function rem_voucher(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/voucher.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Voucher removed!');
            get_vouchers();
        }
        else {
            alert('error', 'Server down!')
        }
    }

    xhr.send('rem_voucher=' + val);
}



window.onload = function () {
    get_vouchers();

}
