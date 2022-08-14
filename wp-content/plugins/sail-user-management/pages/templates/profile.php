<script>
// Ensures alerts auto close after 5 seconds
window.setTimeout(function() {
    var alerts = document.getElementsByClassName("alert");
	for (var i = 0; i < alerts.length; i++) {
		alerts[i].children[0].setAttribute("checked", "checked");
	}
}, 5000);

function setElement(id, value) {
	const urlSearchParams = new URLSearchParams(window.location.search);

	if (id === urlSearchParams.get(value)) {
		document.getElementById(id).style.opacity = "100";
	}
}

window.addEventListener("load",function(event) {
	setElement('success', 'alert');
},false);

</script>

<?php

use Sail\Data\Dao\UserDao;

$dao = UserDao::getInstance();
$sailUser = UserDao::getInstance()->getSailUser();
?>

<div id="success" class="alert success" style="opacity: 0">
		<input type="checkbox" id="alert2"/>
		<label class="close" title="close" for="alert2">
      		<span>X</span>
    	</label>
		<p class="inner">
			Profile successfully updated!
		</p>
</div>
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
