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

    fetch('ajax/profile_manager.php', {
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
    fetch('ajax/profile_manager.php', {
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
    fetch('ajax/profile_manager.php', {
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