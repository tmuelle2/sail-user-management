<script>
const check = function() {
  if (document.getElementById('password').value ==
    document.getElementById('confirmPassword').value) {
    document.getElementById('message').style.color = 'green';
    document.getElementById('message').innerHTML = 'Passwords match :)';
  } else {
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'Passwords do not match!';
  }
}

window.onload = function() {
  const params = (new URL(location)).searchParams;
  console.log("url params: " + params);

  var email = document.getElementById("user_email");
  var pw_reset_key = document.getElementById("pw_reset_key");

  email.value = params.get("user_email");
  pw_reset_key.value = params.get("pw_reset_key");
}
</script>

<form accept-charset="UTF-8" id="user_change_password" autocomplete="on" method="post" action='change-password'>
    <input type="hidden" name="action" value="sail_user_change_password">
    <h5 class="field-label required-field">Password</h5>
    <input name="password" id="password" type="password" class="text-input-field" required onkeyup='check();' /> <br />
    <h5 class="field-label required-field">Confirm Password</h5>
    <input name="confirmPassword" id="confirmPassword" type="password" class="text-input-field" required onkeyup='check();'/> <br />
    <span id='message'></span><br/>
    <input type="hidden" name="user_email" id="user_email">
    <input type="hidden" name="pw_reset_key" id="pw_reset_key">
</form>
<button type="submit" form="user_change_password" value="Submit">Update Password</button>