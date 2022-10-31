<details>
    <summary>Please Verify Your Email</summary>
    <div>
        <p>If you have already gotten a verification email, clicking the link will verify your email and this section will not appear on your profile any more.</p>
        <p>If you can't find your verification email, click the button below to send a new one.</p>
        <form accept-charset="UTF-8" autocomplete="on" action='verify-email'>
            <input type="hidden" name="action" value="sail_user_reverify_email">
        </form>
        <? echo Sail\Utils\HtmlUtils::getSailButton('sail_user_reverify_email', 'Submit') ?>
    </div>
</details>