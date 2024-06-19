
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
    xhr.open('POST', 'ajax/voucher_manager.php', true);
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
    xhr.open('POST', 'ajax/voucher_manager.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        document.getElementById('vouchers-data').innerHTML = this.responseText;

    }
    xhr.send('get_vouchers');
}

function rem_voucher(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/voucher_manager.php', true);
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


let voucher_edit_form = document.getElementById('voucher_edit_form');

function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/voucher_manager.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        let data = JSON.parse(this.responseText);

        voucher_edit_form.elements['voucher_code'].value = data.roomdata.voucher_code;
        voucher_edit_form.elements['voucher_value'].value = data.roomdata.voucher_value;
        voucher_edit_form.elements['voucher_type'].value = data.roomdata.voucher_type;
        voucher_edit_form.elements['booking_min_value'].value = data.roomdata.booking_min_value;
        voucher_edit_form.elements['quantity'].value = data.roomdata.quantity;
        voucher_edit_form.elements['from_date'].value = data.roomdata.from_date;
        voucher_edit_form.elements['to_date'].value = data.roomdata.to_date;
        voucher_edit_form.elements['voucher_desc'].value = data.roomdata.description;
        voucher_edit_form.elements['voucher_id'].value = data.roomdata.id;
    }

    xhr.send('get_voucher=' + id);
}

voucher_edit_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_voucher();
});

function submit_edit_voucher() {
    let data = new FormData();
    data.append('edit_voucher', '');
    data.append('voucher_id', voucher_edit_form.elements['voucher_id'].value);
    data.append('voucher_code', voucher_edit_form.elements['voucher_code'].value);
    data.append('voucher_value', voucher_edit_form.elements['voucher_value'].value);
    data.append('voucher_type', voucher_edit_form.elements['voucher_type'].value);
    data.append('booking_min_value', voucher_edit_form.elements['booking_min_value'].value);
    data.append('quantity', voucher_edit_form.elements['quantity'].value);
    data.append('from_date', voucher_edit_form.elements['from_date'].value);
    data.append('to_date', voucher_edit_form.elements['to_date'].value);
    data.append('voucher_desc', voucher_edit_form.elements['voucher_desc'].value);

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/voucher_manager.php', true);
    xhr.onload = function () {
        var myModal = document.getElementById('edit-voucher');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if (this.responseText == 1) {
            alert('success', 'Voucher data edited!');
            voucher_edit_form.reset();
            get_vouchers();
        } else {
            alert('error', 'Server Down!');
        }
    }

    xhr.send(data);
}

window.onload = function () {
    get_vouchers();

}
