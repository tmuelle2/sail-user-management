<script>
	function onTextFieldChange(value, field) {
		document.getElementById(field).innerHTML = value;
	}

	function onNickNameChange(value) {
		document.getElementById("displayName").innerHTML = value;
	}
</script>
<details>
	<summary>Update Your Friendship Connect Profile</summary>
	<form accept-charset="UTF-8" id="fc-profile-update" autocomplete="off" action='fc-profile-update'>
		<input type="hidden" name="action" value="fc-profile-update">
		<?php wp_nonce_field( 'wp_rest', '__nonce' ); ?>
		<div class="flex-container">
			<div class="flex-child">
				<p>Please acknowledge the following by checking the box.</p>
				<input type="hidden" name="authorized" value="0" />
				<input type="checkbox" name="authorized" value="1" required />
				<label class="field-label required-field" for="authorized">I am either an individual with a disability and I am my own guardian, or I am a person who is legally authorized to complete this Friendship Connect form on behalf of an individual with a disability. SAIL can publish my data and contact information on the SAIL Friendship Connect page on the SAIL website. I understand that other members of Friendship Connect may contact me directly to discuss an interest in becoming friends and/or roommates.</label>
				<h5 class="field-label">Profile Name (For privacy, we recommend not using last names. Ex: use Jane D. not Jane Doe)</h5>
				<input name="nickname" type="text" value="<?php echo $fcProfile->nickname ?>" class="text-input-field" id="nickname" oninput="onNickNameChange(this.value)" /> <br />
				<h5 class="field-label required-field">Profile Date of Birth</h5>
      			<input type="date" name="dob" id="dob" required><br />
				<h5 class="field-label">Profile Picture - Leave Empty to Keep Current Profile Picture</h5>
				<input type="file" id="profilePicture" name="profilePicture" />
				<p id="status"></p>
				<h5 class="field-label required-field">Gender</h5>
				<select name="gender" class="select-field" required>
					<?php
						use Sail\Utils\FormUtils;
						echo FormUtils::populateSelectOption($fcProfile->gender, FormUtils::GENDER_OPTIONS);
					?>
				</select>

				<h5 class="field-label required-field">How do you want other Friendship Connect Users to contact you?</h5>
				<select name="primaryContactType" id="primaryContactType" class="select-field" required>
					<?php
						echo FormUtils::populateSelectOption($fcProfile->primaryContactType, FormUtils::PRIMARY_CONTACT_OPTIONS);
					?>
				</select> <br />

				<h5 class="field-label required-field">Enter the Email or Phone Number you want other Friendship Connect Users to use when contacting you</h5>
				<input name="primaryContact" type="text" value="<?php echo $fcProfile->primaryContact ?>" class="text-input-field" required /> <br />

				<h5 class="field-label required-field">Activities You Enjoy</h5>
				<textarea name="activities" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'activities')"><?php echo $fcProfile->activities ?></textarea><br />
				<h5 class="field-label required-field">Hobbies</h5>
				<textarea name="hobbies" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'hobbies')"><?php echo $fcProfile->hobbies ?></textarea><br />
				<h5 class="field-label required-field">Describe Your Typical Day</h5>
				<textarea name="typicalDay" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'typicalDay')"><?php echo $fcProfile->typicalDay ?></textarea><br />
				<h5 class="field-label required-field">Strengths</h5>
				<textarea name="strengths" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'strengths')"><?php echo $fcProfile->strengths ?></textarea><br />
				<h5 class="field-label required-field">Describe What Makes You Happy</h5>
				<textarea name="makesYouHappy" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'makesYouHappy')"><?php echo $fcProfile->makesYouHappy ?></textarea><br />
				<h5 class="field-label required-field">Life's Vision</h5>
				<textarea name="lifesVision" class="text-input-field" cols="30" rows="2" required oninput="onTextFieldChange(this.value, 'lifesVision')"><?php echo $fcProfile->lifesVision ?></textarea><br />
				<h5 class="field-label">Support Requirements or Dietary Restrictions</h5>
				<textarea name="supportRequirements" class="text-input-field" cols="30" rows="2" oninput="onTextFieldChange(this.value, 'supportRequirements')"><?php echo $fcProfile->supportRequirements ?></textarea><br />
				<h5 class="field-label required-field">What is your SAIL Reference's First and Last Name?</h5>
				<input name="referenceName" value="<?php echo $fcProfile->referenceName ?>" type="text" class="text-input-field" required /> <br />
				<h5 class="field-label required-field">What is your SAIL Reference's Phone Number?</h5>
				<input name="referencePhoneNumber" value="<?php echo $fcProfile->referencePhoneNumber ?>" type="text" class="text-input-field" required /> <br />
				<h5 class="field-label required-field">What is your SAIL Reference's Email?</h5>
				<input name="referenceEmail" value="<?php echo $fcProfile->referenceEmail ?>" type="text" class="text-input-field" required /> <br />
			</div>

			<div class="flex-child">
				<div class="sticky">
					<h5>Friendship Connect Profile Preview</h5>
					<div class="flex-start">
						<div style="margin: 8px 8px 8px 8px;">
							<img class="pfp" id="pfpPreview" alt="Profile Picture" src="<?php echo $fcProfile->profilePicture ?>" />
						</div>
						<div style="margin-left: 32px;">
							<div class="flex-container">
								<h2 id="displayName"><?php echo $fcProfile->nickname ?></h2>
							</div>
							<div class="flex-container"><strong>Activities:&nbsp;</strong><span id="activities"><?php echo $fcProfile->activities ?></span></div>
							<div class="flex-container"><strong>Hobbies:&nbsp;</strong><span id="hobbies"><?php echo $fcProfile->hobbies ?></span></div>
							<div class="flex-container"><strong>Typical Day:&nbsp;</strong><span id="typicalDay"><?php echo $fcProfile->typicalDay ?></span></div>
							<div class="flex-container"><strong>Strengths:&nbsp;</strong><span id="strengths"><?php echo $fcProfile->strengths ?></span></div>
							<div class="flex-container"><strong>What Makes Me Happy:&nbsp;</strong><span id="makesYouHappy"><?php echo $fcProfile->makesYouHappy ?></span></div>
							<div class="flex-container"><strong>Life's Vision:&nbsp;</strong><span id="lifesVision"><?php echo $fcProfile->lifesVision ?></span></div>
							<div class="flex-container"><strong>Support Requirements:&nbsp;</strong><span id="supportRequirements"><?php echo $fcProfile->supportRequirements ?></span></div>
						</div>
					</div>
				</div>
			</div>
		</div>
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