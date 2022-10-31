const BASE64_PREFIX = "data:*/*;base64,";
const LOADING_SPINNER_CLASS = 'loadingButton--loading';
let buttons = [];

function makeFormRestSubmit(formId, apiRoute) {
    const form = document.getElementById(formId);
    form.onsubmit = (function(event) {
        const button = buttons.filter(b => b.getAttribute('form') == formId)[0];
        buttonDisable(button);
        return formRestSubmit(form, button, apiRoute, event)
    });
}

function buttonDisable(button) {
    button.disabled = true;
    button.classList.add(LOADING_SPINNER_CLASS);
}

function buttonEnable(button) {
    button.disabled = false;
    button.classList.remove(LOADING_SPINNER_CLASS);
}

function formRestSubmit(form, button, apiRoute, event) {
    const fileInput = document.querySelector('input[type=file]');
    const formData = new FormData(form);
    let reader = null;

    for(var kvPair of formData.entries()) {
        if (Array.isArray(formData.getAll(kvPair[0])) && formData.getAll(kvPair[0]).length > 1) {
            formData.set(kvPair[0], formData.getAll(kvPair[0]).join('|'));
        } else if(fileInput && kvPair[0] === fileInput.id) {
            reader = new FileReader();
        }
    }

    if (reader) {
        reader.addEventListener("load", function () {
            let fileContents = reader.result;
            if (fileContents.startsWith(BASE64_PREFIX)) {
                fileContents = fileContents.slice(BASE64_PREFIX.length);
            }
            formData.set(fileInput.id, fileContents);
            postData(apiRoute, formData, button);
        }, false);
        if (fileInput.files.length !== 0)
            reader.readAsDataURL(fileInput.files[0]);
        else
            postData(apiRoute, formData, button);
    } else {
        postData(apiRoute, formData, button);
    };

    return false;
}

function postData(apiRoute, formData, button) {
    const formHeaders = new Headers({
        'Content-Type': 'application/json',
        'x-wp-nonce': SAIL.nonce
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
            buttonEnable(button);
            return response.json();
        })
        .catch(error => {
            buttonEnable(button);
            console.error(error)
            return false;
        });
    return false;
}

window.addEventListener('load', (event) => {
    const sailForms = document.querySelectorAll('form[action]')
    sailForms.forEach(form => makeFormRestSubmit(form.id,  SAIL.formsApiUrl + form.getAttribute('action')));
    buttons = [...document.getElementsByTagName('button')];
});


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

// TODO: Rename 
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