<script type="text/javascript">
    makeFormRestSubmit('user_forgot_password', <? use Sail\Constants; echo Constants::FORM_REST_PREFIX . 'forgot-password'; ?>);
  </script>

<form accept-charset="UTF-8" id="user_forgot_password" autocomplete="on">
    <input type="hidden" name="action" value="sail_user_forgot_password">
    <h5 class="field-label required-field">Email</h5>
    <input name="email" type="email" class="text-input-field" required /> <br /> 
</form>
<button type="submit" form="user_forgot_password" value="Submit">Send Password Reset Email</button>