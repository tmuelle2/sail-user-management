<?php
use Sail\Data\Dao\FriendshipConnectDao;
use Sail\Data\Dao\UserDao;

$sailUser = UserDao::getInstance()->getSailUser();
$fcMember = FriendshipConnectDao::getInstance()->getFcProfile();
?>
<script>
  function onTextFieldChange(value, field) {
    document.getElementById(field).innerHTML = value;
  }

  function onNickNameChange(value) {
    document.getElementById("displayName").innerHTML = value;
  }
</script>

<form accept-charset="UTF-8" id="fc-registration" autocomplete="on" action='fc-registration'>
  <input type="hidden" name="action" value="fc-registration">
  <!--<p>To see an example of what a filled out Friendship Connect Profile looks like click here:&nbsp;<a target="_blank" href="https://sailhousingsolutions.org/friendship-connect-example-profile">Example Friendship Connect Profile</a></p>-->
  <div class="flex-container">
    <div class="flex-child">
      <p>Please acknowledge the following by checking the box.</p>
      <input type="hidden" name="authorized" value="0" />
      <input type="checkbox" name="authorized" value="1" required />
      <label class="field-label required-field" for="authorized">I am either an individual with a disability and I am my own guardian, or I am a person who is legally authorized to complete this Friendship Connect form on behalf of an individual with a disability. SAIL can publish my data and contact information on the SAIL Friendship Connect page on the SAIL website. I understand that other members of Friendship Connect may contact me directly to discuss an interest in becoming friends and/or roommates.</label>
      <h5 class="field-label">Profile Name (For privacy, we recommend not using last names. Ex: use Jane D. not Jane Doe)</h5>
      <input name="nickname" type="text" class="text-input-field" id="nickname" oninput="onNickNameChange(this.value)" /> <br />
      <h5 class="field-label">First Name (Not shown to other users)</h5>
      <input name="firstName" type="text" class="text-input-field" id="firstName" /> <br />
      <h5 class="field-label">Last Name (Not shown to other users)</h5>
      <input name="lastName" type="text" class="text-input-field" id="lastName" /> <br />
      <h5 class="field-label required-field">Profile Date of Birth</h5>
      <input type="date" name="dob" id="dob" required><br />
      <h5 class="field-label">Profile Picture</h5>
      <input type="file" id="profilePicture" name="profilePicture" multiple="false" />
      <p id="status"></p>
      <h5 class="field-label required-field">City</h5>
				<input name="city" type="text" class="text-input-field" id="city" required /> <br />
				<h5 class="field-label required-field">State</h5>
				<select name="state" required class="select-field" style="width: 100%">
					<?php
					use Sail\Utils\FormUtils;
					echo FormUtils::populateSelectOption('MI', FormUtils::STATE_OPTIONS);
					?>
				</select> <br />
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
      <p>
      For the safety and security of those choosing to use Friendship Connect please supply a reference that SAIL can contact. This reference <strong>should not</strong> be a relative or yourself. Possible references could be a current SAIL member, your Supports Coordinator, a Manager at work, a High School or Post High School Teacher. Please contact your reference and let them know that someone from SAIL will be in contact with them.
      </p>
      <h5 class="field-label required-field">What is your Reference's First and Last Name?</h5>
      <input name="referenceName" type="text" class="text-input-field" required /> <br />
      <h5 class="field-label required-field">What is your relation to your Reference? (ex: Teacher, Supports Coordinator, Work Manager, etc.)</h5>
      <input name="referenceRelation" type="text" class="text-input-field" required /> <br />
      <h5 class="field-label required-field">What is your Reference's Phone Number?</h5>
      <input name="referencePhoneNumber" type="text" class="text-input-field" required /> <br />
      <h5 class="field-label required-field">What is your Reference's Email?</h5>
      <input name="referenceEmail" type="text" class="text-input-field" required /> <br />
    </div>

    <div class="flex-child">
      <div class="sticky">
        <h5>Friendship Connect Profile Preview</h5>
        <div class="flex-start">
          <div style="margin: 8px 8px 8px 8px;">
            <img class="pfp" id="pfpPreview" alt="Profile Picture" src="/wp-admin/identicon.php?size=200&hash=<?php echo md5($sailUser->email) ?>"/>
          </div>
          <div style="margin-left: 32px;">
            <div class="flex-container">
              <h2 id="displayName"><?php echo "(Your Profile Name)" ?></h2>
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
</form>
<? echo Sail\Utils\HtmlUtils::getSailButton('fc-registration', 'Submit') ?>

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