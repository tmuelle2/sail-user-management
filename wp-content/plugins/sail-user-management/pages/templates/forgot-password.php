<form accept-charset="UTF-8" id="user_forgot_password" autocomplete="on" action='forgot-password'>
    <input type="hidden" name="action" value="sail_user_forgot_password">
    <h5 class="field-label required-field">Email</h5>
    <input name="email" type="email" class="text-input-field" required /> <br />
</form>
<? echo Sail\Utils\HtmlUtils::getSailButton('user_forgot_password', 'Send Password Reset Email') ?>