<?php
use Sail\Data\Dao\FriendshipConnectDao;
use Sail\Data\Dao\UserDao;

$sailUser = UserDao::getInstance()->getSailUser();
$fcMember = FriendshipConnectDao::getInstance()->getFcProfile();
$displayName = $sailUser->firstName;
if ($fcMember->namePreference == "First Name and Last Initial") {
	$displayName = "{$sailUser->firstName} {$sailUser->lastName[0]}.";
} else if ($fcMember->namePreference == "Nickname") {
	$displayName = $fcMember->nickname;
}
?>
<script>
	function onNamePrefChange(selectObj) {
		var value = selectObj.value;
		if (value === "First Name Only") {
			document.getElementById("displayName").innerHTML = document.getElementById("firstName").innerHTML;
		} else if (value === "First Name and Last Initial") {
			document.getElementById("displayName").innerHTML = document.getElementById("firstNameAndLastInitial").innerHTML;
		} else if (value === "Nickname") {
			document.getElementById("displayName").innerHTML = document.getElementById("nickname").value;
		}
	}

	function onTextFieldChange(value, field) {
		document.getElementById(field).innerHTML = value;
	}

	function onNickNameChange(value) {
		if (document.getElementById("namePreference").value === "Nickname") {
			document.getElementById("displayName").innerHTML = value;
		}
	}

	window.addEventListener("load", function() {
		document.getElementById("activities").innerHTML = document.getElementsByName("activities")[0].value;
		document.getElementById("hobbies").innerHTML = document.getElementsByName("hobbies")[0].value;
		document.getElementById("typicalDay").innerHTML = document.getElementsByName("typicalDay")[0].value;
		document.getElementById("strengths").innerHTML = document.getElementsByName("strengths")[0].value;
		document.getElementById("makesYouHappy").innerHTML = document.getElementsByName("makesYouHappy")[0].value;
		document.getElementById("lifesVision").innerHTML = document.getElementsByName("lifesVision")[0].value;
		document.getElementById("supportRequirements").innerHTML = document.getElementsByName("supportRequirements")[0].value;
		var name = {
			value: document.getElementById("namePreference").value
		};
		onNamePrefChange(name);
	});
</script>
<details>
	<summary>Update Your Friendship Connect Profile</summary>
	<form accept-charset="UTF-8" id="fc-profile-update" autocomplete="off" action='fc-profile-update'>
		<input type="hidden" name="action" value="fc-profile-update">

		<div class="flex-container">
			<div class="flex-child">
				<p>Please acknowledge the following by checking the box.</p>
				<input type="hidden" name="authorized" value="0" />
				<input type="checkbox" name="authorized" value="1" required />
				<label class="field-label required-field" for="authorized">I am either an individual with a disability and I am my own guardian, or I am a person who is legally authorized to complete this Friendship Connect form on behalf of an individual with a disability. SAIL can publish my data and contact information on the SAIL Friendship Connect page on the SAIL website. I understand that other members of Friendship Connect may contact me directly to discuss an interest in becoming friends and/or roommates.</label>
				<!--<h5 class="field-label required-field">Are you legally authorized to complete this form (either on behalf of yourself or a dependent)?</h5>
			      <input name="authorized" type="radio" value="1" /> Yes<br />
			      <input name="authorized" type="radio" value="0" /> No<br />
			      <h5 class="field-label required-field">Do you consent to filling out this form and allowing the information to be shared with other users within Friendship Connect?</h5>
			      <input name="consent" type="radio" value="1" /> Yes<br />
			      <input name="consent" type="radio" value="0" /> No<br /> -->
				<h5 class="field-label required-field">How do you want your Name to appear to others in Friendship connect?</h5>
				<select name="namePreference" id="namePreference" class="select-field" onchange="onNamePrefChange(this)" required>
					<option value="First Name Only">First Name Only</option>
					<option value="First Name and Last Initial">First Name and Last Initial</option>
					<!--<option value="Initials Only">Initials Only</option>-->
					<option value="Nickname">Nickname</option>
				</select> <br />
				<h5 class="field-label">Nickname</h5>
				<input name="nickname" type="text" class="text-input-field" id="nickname" oninput="onNickNameChange(this.value)" /> <br />

				<h5 class="field-label">Profile Picture - Leave Empty to Keep Current Profile Picture</h5>
				<input type="file" id="profilePicture" name="profilePicture" />
				<p id="status"></p>
				<h5 class="field-label required-field">Gender</h5>
				<select name="gender" class="select-field" required>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Other">Other</option>
					<option value="Prefer not to say">Prefer not to say</option>
				</select>

				<h5 class="field-label required-field">How do you want other Friendship Connect Users to contact you?</h5>
				<select name="primaryContactType" id="primaryContactType" class="select-field" required>
					<option value="Email">Email</option>
					<option value="Phone (Text Message)">Phone (Text Message)</option>
					<option value="Phone (Voice Call)">Phone (Voice Call)</option>
				</select> <br />

				<h5 class="field-label required-field">Enter the Email or Phone Number you want other Friendship Connect Users to use when contacting you</h5>
				<input name="primaryContact" type="text" class="text-input-field" required /> <br />

				<h5 class="field-label required-field">Activities You Enjoy</h5>
				<textarea name="activities" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'activities')"></textarea><br />
				<h5 class="field-label required-field">Hobbies</h5>
				<textarea name="hobbies" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'hobbies')"></textarea><br />
				<h5 class="field-label required-field">Describe Your Typical Day</h5>
				<textarea name="typicalDay" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'typicalDay')"></textarea><br />
				<h5 class="field-label required-field">Strengths</h5>
				<textarea name="strengths" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'strengths')"></textarea><br />
				<h5 class="field-label required-field">Describe What Makes You Happy</h5>
				<textarea name="makesYouHappy" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'makesYouHappy')"></textarea><br />
				<h5 class="field-label required-field">Life's Vision</h5>
				<textarea name="lifesVision" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'lifesVision')"></textarea><br />
				<h5 class="field-label">Support Requirements or Dietary Restrictions</h5>
				<textarea name="supportRequirements" class="text-input-field" cols="30" rows="2" oninput="onTextFieldChange(this.value, 'supportRequirements')"></textarea><br />
				<h5 class="field-label required-field">What is your SAIL Reference's First and Last Name?</h5>
				<input name="referenceName" type="text" class="text-input-field" required /> <br />
				<h5 class="field-label required-field">What is your SAIL Reference's Phone Number?</h5>
				<input name="referencePhoneNumber" type="text" class="text-input-field" required /> <br />
				<h5 class="field-label required-field">What is your SAIL Reference's Email?</h5>
				<input name="referenceEmail" type="text" class="text-input-field" required /> <br />
			</div>

			<div class="flex-child">
				<div class="sticky">
					<h5>Friendship Connect Profile Preview</h5>
					<div class="flex-start">
						<div style="margin: 8px 8px 8px 8px;">
							<img class="pfp" id="pfpPreview" alt="Profile Picture" src="<?php echo $fcMember->profilePicture ?>" />
						</div>
						<div style="margin-left: 32px;">
							<div class="flex-container">
								<h2 id="displayName"><?php echo $displayName ?></h2>
							</div>
							<div class="flex-container"><strong>Activities:&nbsp;</strong><span id="activities"></span></div>
							<div class="flex-container"><strong>Hobbies:&nbsp;</strong><span id="hobbies"></span></div>
							<div class="flex-container"><strong>Typical Day:&nbsp;</strong><span id="typicalDay"></span></div>
							<div class="flex-container"><strong>Strengths:&nbsp;</strong><span id="strengths"></span></div>
							<div class="flex-container"><strong>What Makes Me Happy:&nbsp;</strong><span id="makesYouHappy"></span></div>
							<div class="flex-container"><strong>Life's Vision:&nbsp;</strong><span id="lifesVision"></span></div>
							<div class="flex-container"><strong>Support Requirements:&nbsp;</strong><span id="supportRequirements"></span></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div style="display: none" id="firstName"><?php echo $sailUser->firstName ?></div>
		<div style="display: none" id="firstNameAndLastInitial"><?php echo "{$sailUser->firstName} {$sailUser->lastName[0]}." ?></div>
		<div style="display: none" id="initials"><?php echo strtoupper("{$sailUser->firstName[0]}.{$sailUser->lastName[0]}.") ?></div>
	</form>
	<button type="submit" form="fc-profile-update" value="Submit">Submit</button>
</details>

<!-- Profile Picture preview from https://web.dev/read-files/ -->
<script>
	const status = document.getElementById('status');
	const pfpPreview = document.getElementById('pfpPreview');
	if (window.FileList && window.File && window.FileReader) {
		document.getElementById('profilePicture').addEventListener('change', event => {
			pfpPreview.src = '';
			status.textContent = '';
			const file = event.target.files[0];
			if (!file.type) {
				status.textContent = 'Error: The File.type property does not appear to be supported on this browser.';
				return;
			}
			if (!file.type.match('image.*')) {
				status.textContent = 'Error: The selected file does not appear to be an image.'
				return;
			}
			const reader = new FileReader();
			reader.addEventListener('load', event => {
				pfpPreview.src = event.target.result;
			});
			reader.readAsDataURL(file);
		});
	}
</script>