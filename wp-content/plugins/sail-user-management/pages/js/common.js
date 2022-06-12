function registerDobValidator(inputId) {
    const dtToday = new Date();
    const month = dtToday.getMonth() + 1;
    const day = dtToday.getDate();
    const year = dtToday.getFullYear() - 18;
    if (month < 10)
        month = '0' + month.toString();
    if (day < 10)
        day = '0' + day.toString();
    const dobCutoff = year + '-' + month + '-' + day;

    const input = document.getElementById(inputId);
    input.setAttribute("max", dobCutoff);
    input.addEventListener('invalid', function (event) {
        if (event.target.validity.rangeOverflow) {
            event.target.setCustomValidity('We are sorry. You are not eligible to Join SAIL. You must be 18 years or older to join.');
        }
    })
    input.addEventListener('change', function (event) {
        event.target.setCustomValidity('');
    });
}

function makeFormRestSubmit(formId, apiRoute) {
    const form = document.getElementById(formId);
    form.onsubmit = (function (event) { return formRestSubmit(form, apiRoute, event) });
}

function formRestSubmit(form, apiRoute, event) {
    const formData = new FormData(form);

    for(var kvPair of formData.entries()) {
        if (Array.isArray(formData.getAll(kvPair[0])) && formData.getAll(kvPair[0]).length > 1) {
            formData.set(kvPair[0], formData.getAll(kvPair[0]).join('|'));
        }
    }
    console.log("!!!!!!!!!!!!!formRestSubmit: ");
    console.log("event: ");
    console.log(event);
    console.log("formData: ")
    console.log(Object.fromEntries(formData)["__nonce"]);
    const formHeaders = new Headers({
        'Content-Type': 'application/json',
        'x-wp-nonce': Object.fromEntries(formData)["__nonce"] ? Object.fromEntries(formData)["__nonce"] : undefined
    });

    fetch(apiRoute, {
        method: 'POST',
        headers: formHeaders,
        body: JSON.stringify(Object.fromEntries(formData))
    })
        .then(response => {
            if (response.headers.get('Location')) {
                location.href = response.headers.get('Location');
            }
            if (response.redirected) {
                location.href = response.url;
            }
            return response.json();
        })
        .catch(error => console.error(error));
    return false;
}

var check = function () {
    if (document.getElementById('password').value ==
        document.getElementById('confirmPassword').value) {
        document.getElementById('message').style.color = 'green';
        document.getElementById('message').innerHTML = 'Passwords match :)';
    } else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Passwords do not match!';
    }
}