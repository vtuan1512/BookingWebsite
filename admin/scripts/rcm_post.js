
let food_s_form = document.getElementById('food_s_form');
let post_s_form = document.getElementById('post_s_form');

food_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_food();
})

function add_food() {
    let data = new FormData();
    data.append('name', food_s_form.elements['food_name'].value);
    data.append('image', food_s_form.elements['food_icon'].files[0]);
    data.append('desc', food_s_form.elements['food_desc'].value);


    data.append('add_food', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/rcm_post.php', true);
    xhr.onload = function () {
        var myModal = document.getElementById('food-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if (this.responseText == 'inv-img') {
            alert('error', 'Only PNG,JEPG,WEBG format allowed !');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Image upload failed, its should be less than 1MB !');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image upload failed. Server Down!');
        } else {
            alert('success', 'New food post added !');
            food_s_form.reset();
            get_foods();
        }
    }

    xhr.send(data);
}

function get_foods() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/rcm_post.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        document.getElementById('foods-data').innerHTML = this.responseText;

    }
    xhr.send('get_foods');
}

function rem_food(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/rcm_post.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Food post removed!');
            get_foods();
        }
        else {
            alert('error', 'Server down!')
        }
    }

    xhr.send('rem_food=' + val);
}

post_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_post();
})

function add_post() {
    let data = new FormData();
    data.append('name', post_s_form.elements['post_name'].value);
    data.append('image', post_s_form.elements['post_icon'].files[0]);
    data.append('desc', post_s_form.elements['post_desc'].value);


    data.append('add_post', '');

    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/rcm_post.php', true);
    xhr.onload = function () {
        var myModal = document.getElementById('post-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();
        if (this.responseText == 'inv-img') {
            alert('error', 'Only PNG,JEPG,WEBG format allowed !');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Image upload failed, its should be less than 1MB !');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image upload failed. Server Down!');
        } else {
            alert('success', 'New destination post added !');
            post_s_form.reset();
            get_posts();
        }
    }

    xhr.send(data);
}

function get_posts() {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/rcm_post.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        document.getElementById('posts-data').innerHTML = this.responseText;

    }
    xhr.send('get_posts');
}


function rem_post(val) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'ajax/rcm_post.php', true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Destination post removed!');
            get_posts();
        }
        else {
            alert('error', 'Server down!')
        }
    }

    xhr.send('rem_post=' + val);
}

window.onload = function () {
    get_foods();
    get_posts();
}
