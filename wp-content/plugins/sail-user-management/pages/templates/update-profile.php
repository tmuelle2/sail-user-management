<details>
  <summary>Update Your Profile</summary>
  <form accept-charset="UTF-8" id="user_profile_update" autocomplete="on" action='user-update'>
    <input type="hidden" name="action" value="sail_user_update">
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">First Name</h5>
        <input name="firstName" type="text" value="<?php echo $sailUser->firstName ?>" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label required-field">Last Name</h5>
        <input name="lastName" type="text" value="<?php echo $sailUser->lastName ?>" class="text-input-field" required /> <br />
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">Email</h5>
        <input name="email" type="email" value="<?php echo $sailUser->email ?>" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label required-field">Phone Number</h5>
        <input name="phoneNumber" type="tel" value="<?php echo $sailUser->phoneNumber ?>" class="text-input-field" placeholder="3215556789" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" maxlength="10" title="Ten digit phone number" required />
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">Address Line 1</h5>
        <input name="addrLine1" type="text" value="<?php echo $sailUser->addrLine1 ?>" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label">Address Line 2</h5>
        <input name="addrLine2" type="text" value="<?php echo $sailUser->addrLine2 ?>" class="text-input-field" /> <br />
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">City</h5>
        <input name="city" type="text" value="<?php echo $sailUser->city ?>" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label required-field">State</h5>
        <select name="state" required class="select-field" style="width: 100%">
          <?php
          use Sail\Utils\FormUtils;
          echo FormUtils::populateSelectOption($sailUser->state, FormUtils::STATE_OPTIONS);
          ?>
        </select> <br />
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">Zip Code</h5>
        <input name="zipCode" type="number" value="<?php echo $sailUser->zipCode ?>"  maxlength="5" required style="	min-height: 26px;
            font-size: 16px;" /> <br />
      </div>
      <div class="flex-child">
        <!--blank-->
      </div>
    </div>
    <h5 class="field-label">Date of Birth</h5>
    <input type="date" name="dob" value="<?php echo $sailUser->dob ?>"><br />
    <h5 class="field-label required-field">Which of the following best describes your situation? (Check all that apply)</h5>
    <?php echo FormUtils::populateMultiSelectChecbox(explode("|", $sailUser->situation), FormUtils::SITUATION_CHECKBOXES) ?>
    <h5 class="field-label required-field">Which Role best describes you?</h5>
    <select name="role" class="select-field" required>
    <?php echo FormUtils::populateSelectOption($sailUser->role, FormUtils::ROLE_OPTIONS) ?>
    </select>
    <br />
    <h5 class="field-label">How did you hear about SAIL?</h5>
    <input name="reference" type="text" value="<?php echo $sailUser->reference ?>" class="text-input-field" /> <br />
    <h5 class="field-label required-field">What is your housing solution timeframe?</h5>
    <select name="timeframe" class="select-field" required>
    <?php echo FormUtils::populateSelectOption($sailUser->timeframe, FormUtils::TIMEFRAME_OPTIONS) ?>
    </select><br />
    <h5 class="field-label">Can we contact you via Email?</h5>
    <?php echo FormUtils::populateRadio("contactViaEmail", $sailUser->contactViaEmail, FormUtils::CONTACT_EMAIL_RADIO) ?>
    <h5 class="field-label">Can we contact you via Text?</h5>
    <?php echo FormUtils::populateRadio("contactViaText", $sailUser->contactViaText, FormUtils::CONTACT_TEXT_RADIO) ?>
    <h5 class="field-label">Would you like to receive the SAIL Newsletter, get updates from SAIL, and hear about upcoming SAIL events?</h5>
    <?php echo FormUtils::populateRadio("newsletter", $sailUser->newsletter, FormUtils::NEWSLETTER_RADIO) ?>
    <h5 class="field-label">How can SAIL assist you?</h5>
    <textarea name="additionalInfo" class="text-input-field" cols="30" rows="2"><?php echo $sailUser->additionalInfo ?></textarea><br />
  </form>
  <button type="submit" form="user_profile_update" value="Submit">Submit</button>
</details>