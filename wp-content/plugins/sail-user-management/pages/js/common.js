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