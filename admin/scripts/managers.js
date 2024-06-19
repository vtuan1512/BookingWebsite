
function get_managers() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/managers.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        document.getElementById('managers-data').innerHTML = this.responseText;
    }

    xhr.send('get_managers');

}

function toggle_status(id, val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/managers.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {

        if (this.responseText == 1) {
            alert('success', 'Status Changed !');
            get_users();
        } else {
            alert('error', 'Server Down!');
        }
    }

    xhr.send('toggle_status=' + id + '&value=' + val);

}

function remove_manager(manager_id) {

    if (confirm("Are you sure, you want to remove this user?")) {
        let data = new FormData();
        data.append('manager_id', manager_id);
        data.append('remove_manager', '');
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/managers.php', true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert('success', 'Manager Removed !');
                get_users();
            }
            else {
                alert('error', 'Manager remove Failed!');

            }
        }
        xhr.send(data);

    }

}
function search_manager(username){
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/managers.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
        document.getElementById('managers-data').innerHTML = this.responseText;
    }

    xhr.send('search_manager&name='+username);

} 

window.onload = function () {
    get_managers();
}