<form accept-charset="UTF-8" id="user_signon" autocomplete="on" action='login'>
    <input type="hidden" name="action" value="sail_user_signon">
    <h5 class="field-label required-field">Email</h5>
    <input name="email" type="email" class="text-input-field" required /> <br />
    <h5 class="field-label required-field">Password</h5>
    <input name="password" type="password" class="text-input-field" required /> <br />
    <input name="remember" type="checkbox" />
    <input name="remember" value="0" type="hidden">
    <input type="hidden" name="redirect_to" id="redirect_to">
    <label for="remember"> Remember me</label><br>
</form>
<a style="font-size: 16px;" href="<? echo Sail\Utils\WebUtils::getUrl('/forgot-password') ?>">Forgot your password?</a>
<div class="wp-block-button">
    <? echo Sail\Utils\HtmlUtils::getSailButton('user_signon', 'Login') ?>
</div>
