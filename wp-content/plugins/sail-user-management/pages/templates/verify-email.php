<script type="text/javascript">
  makeFormRestSubmit('sail_user_reverify_email', <? use Sail\Constants; echo Constants::FORM_REST_PREFIX . 'verify-email'; ?>);
</script>

<details>
    <summary>Please Verify Your Email</summary>
    <div>
        <p>If you have already gotten a verification email, clicking the link will verify your email and this section will not appear on your profile any more.</p>
        <p>If you can't find your verrification email, click the button below to send a new one.</p>
        <form accept-charset="UTF-8" autocomplete="on"">
            <input type="hidden" name="action" value="sail_user_reverify_email">
        </form>
        <button type="submit" form="sail_user_reverify_email" value="Submit">Send Email</button>
    </div>
</details>