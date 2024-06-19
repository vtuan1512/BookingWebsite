<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - PROFILE</title>

</head>
<style>
    img.course_qr_img {
        width: 477px;
    }
</style>

<body class="bg-light">
    <?php require('inc/header.php');
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }
    $u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", [$_SESSION['uId']], 's');

    if (mysqli_num_rows($u_exist) == 0) {
        redirect('index.php');
    }

    $u_fetch = mysqli_fetch_assoc($u_exist);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">PROFILE</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">PROFILE</a>
                </div>
            </div>

            <div class="col-12 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="info-form">
                        <h5 class="mb-3 fw-bold">BASIC INFORMATION</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name</label>
                                <input name="name" value="<?php echo $u_fetch['name'] ?>" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input name="phonenum" value="<?php echo $u_fetch['phonenum'] ?>" type="number" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of birth</label>
                                <input name="dob" value="<?php echo $u_fetch['dob'] ?>" type="date" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pincode</label>
                                <input name="pincode" value="<?php echo $u_fetch['pincode'] ?>" type="number" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $u_fetch['address'] ?>
                                </textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark shadow-none ">Save Changes</button>
                        <button type="button" class="btn btn-dark shadow-none" id="info-form-reset">Reset</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="profile-form">
                        <h5 class="mb-3 fw-bold">PICTURE:</h5>
                        <img src="<?php echo USERS_IMG_PATH . $u_fetch['profile'] ?>" class=" img-fluid mb-3">
                        <label class="form-label fw-bold">New Picture:</label>
                        <input name="profile" type="file" accept="image/jpg, image/jpeg, image/png, image/webp" class="mb-4 form-control shadow-none">
                        <button type="submit" class="btn btn-dark shadow-none ">Save Changes</button>
                        <button type="button" class="btn btn-dark shadow-none" id="info-form-reset">Reset</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="pass-form">
                        <h5 class="mb-3 fw-bold">RESET PASSWORD:</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>
                                <input name="new_pass" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input name="confirm_pass" class="form-control shadow-none" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark shadow-none ">Save Changes</button>
                        <button type="button" class="btn btn-dark shadow-none" id="info-form-reset">Reset</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <?php
    require('inc/footer.php');
    ?>


    <script>
        let info_form = document.getElementById('info-form');

        info_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('info_form', '');
            formData.append('name', info_form.elements['name'].value);
            formData.append('phonenum', info_form.elements['phonenum'].value);
            formData.append('address', info_form.elements['address'].value);
            formData.append('pincode', info_form.elements['pincode'].value);
            formData.append('dob', info_form.elements['dob'].value);

            fetch('ajax/profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.text();
                })
                .then(responseText => {
                    console.log('Server Response:', responseText);
                    if (responseText.trim() === 'phone_already') {
                        alert('error', 'Phone number is already registered!');
                    } else if (responseText.trim() === '0') {
                        alert('error', 'Something went wrong!');
                    } else if (responseText.trim() === '1') {
                        alert('success', 'Profile Updated!');
                    } else {
                        console.error('Unexpected response:', responseText);
                        alert('error', 'Unexpected response from server.');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('error', 'An error occurred. Please try again.');
                });
        });

        let profile_form = document.getElementById('profile-form');
        profile_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('profile_form', '');
            formData.append('profile', profile_form.elements['profile'].files[0]);
            fetch('ajax/profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.text();
                })
                .then(responseText => {
                    console.log('Server Response:', responseText);
                    if (responseText.trim() === 'inv_img') {
                        alert('error', "Only JPG,WEBP & PNG images are allowed !");
                    } else if (responseText.trim() === 'upd_failed') {
                        alert('error', 'Image upload failed!');
                    } else if (responseText.trim() === '0') {
                        alert('error', 'Update Failed !');
                    } else {
                        window.location.href = window.location.pathname;
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('error', 'An error occurred. Please try again.');
                });
        });

        let pass_form = document.getElementById('pass-form');
        pass_form.addEventListener('submit', function(e) {
            e.preventDefault();

            let new_pass = pass_form.elements['new_pass'].value;
            let confirm_pass = pass_form.elements['confirm_pass'].value;

            if (new_pass != confirm_pass) {
                alert('error', 'Password not match!');
                return false;
            }

            let formData = new FormData();
            formData.append('pass_form', '');
            formData.append('new_pass', new_pass);
            formData.append('confirm_pass', confirm_pass);
            fetch('ajax/profile.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.text();
                })
                .then(responseText => {
                    console.log('Server Response:', responseText);
                    if (responseText.trim() === 'mismatch') {
                        alert('error', "Password do not match !");
                    } else if (responseText.trim() === '0') {
                        alert('error', 'Update Failed !');
                    } else {
                        alert('success', 'Changes Saved!');
                        pass_form.reset();
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('error', 'An error occurred. Please try again.');
                });
        });
        document.addEventListener('DOMContentLoaded', (event) => {
            const forms = ['info-form', 'profile-form', 'pass-form'];
            const initialValues = {};

            forms.forEach(formId => {
                let form = document.getElementById(formId);
                if (form) {
                    initialValues[formId] = {};

                    Array.from(form.elements).forEach(element => {
                        if (element.name) {
                            if (element.type === 'checkbox' || element.type === 'radio') {
                                initialValues[formId][element.name] = element.checked;
                            } else {
                                initialValues[formId][element.name] = element.value;
                            }
                        }
                    });

                    let resetButton = document.getElementById(`${formId}-reset`);
                    if (resetButton) {
                        resetButton.addEventListener('click', () => {
                            Array.from(form.elements).forEach(element => {
                                if (element.name) {
                                    if (element.type === 'checkbox' || element.type === 'radio') {
                                        element.checked = initialValues[formId][element.name];
                                    } else {
                                        element.value = initialValues[formId][element.name];
                                    }
                                }
                            });
                        });
                    }
                }
            });
        });
    </script>


</body>

</html>