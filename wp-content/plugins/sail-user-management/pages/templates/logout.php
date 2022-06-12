<form accept-charset="UTF-8" id="user_logout" autocomplete="on" action='logout'>
    <?php wp_nonce_field( 'wp_rest', '__nonce' ); ?>
    <input type="hidden" name="action" value="sail_user_logout">
</form>
<div class="wp-block-button">
	<button style="border: none;" class="wp-block-button__link has-white-color has-vivid-cyan-blue-background-color has-text-color has-background" type="submit" form="user_logout" value="Submit">Logout</button>
</div>