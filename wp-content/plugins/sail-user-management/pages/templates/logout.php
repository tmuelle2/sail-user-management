<form accept-charset="UTF-8" id="user_logout" autocomplete="on" action='logout'>
    <input type="hidden" name="action" value="sail_user_logout">
</form>
<div class="wp-block-button">
    <? echo Sail\Utils\HtmlUtils::getSailButton('user_logout', 'Logout') ?>
</div>