<?php

use Sail\Data\Dao\UserDao;

$dao = UserDao::getInstance();
$sailUser = UserDao::getInstance()->getSailUser();
?>
<div style="margin-top: 64px;" class="flex-start">
	<div style="margin-left: 32px;">
		<div class="flex-container">
			<h2><?php echo "{$sailUser->firstName} {$sailUser->lastName}" ?></h2>
		</div>
		<div class="flex-container"><strong>Email:&nbsp;</strong><span><?php echo $sailUser->email ?></span></div>
		<div class="flex-container"><strong>Phone:&nbsp;</strong><span><?php echo $sailUser->phoneNumber ?></span></div>
		<div class="flex-container"><strong>Address:&nbsp;</strong><span><?php echo "{$sailUser->addrLine1} {$sailUser->addrLine2}" ?></span></div>
		<span style="padding-left: 90px;"><?php echo "{$sailUser->city}, {$sailUser->state}, {$sailUser->zipCode}" ?></span>
	</div>
</div>
<br>



<br>
<!--TEMP CODE: USING TO TEST SHORTCODES
<php echo do_shortcode('[userAddFamilyMember]'); ?>
<php echo do_shortcode('[userUpdateProfile]'); ?>
<php echo do_shortcode('[userFCProfileUpdate]'); ?>-->
