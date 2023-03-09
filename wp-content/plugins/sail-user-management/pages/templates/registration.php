<form accept-charset="UTF-8" id="user_reg" autocomplete="on" action='user-registration'>
	<div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">First Name</h5>
        <input name="firstName" type="text" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label required-field">Last Name</h5>
        <input name="lastName" type="text" class="text-input-field" required /> <br />
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">Email</h5>
        <input name="email" type="email" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label required-field">Phone Number</h5>
        <input name="phoneNumber" type="tel" class="text-input-field" placeholder="3215556789" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" maxlength="10"  title="Ten digit phone number" required/>
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
          <h5 class="field-label required-field">Create Password</h5>
          <input name="password" type="password" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
          <!--blank-->
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">Address Line 1</h5>
        <input name="addrLine1" type="text" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label">Address Line 2</h5>
        <input name="addrLine2" type="text" class="text-input-field" /> <br />
      </div>
    </div>
    <div class="flex-container">
      <div class="flex-child">
        <h5 class="field-label required-field">City</h5>
        <input name="city" type="text" class="text-input-field" required /> <br />
      </div>
      <div class="flex-child">
        <h5 class="field-label required-field">State</h5>
          <select name="state" required class="select-field" style="width: 100%">
            <?php
            use Sail\Utils\FormUtils;
            echo FormUtils::populateSelectOption('MI', FormUtils::STATE_OPTIONS);
            ?>
          </select> <br />
      </div>
    </div>
	<div class="flex-container">
    	<div class="flex-child">
          <h5 class="field-label required-field">Zip Code</h5>
          <input name="zipCode" type="number" maxlength="5" required style="	min-height: 26px;
          font-size: 16px;" /> <br />
      	</div>
        <div class="flex-child">
          <!--blank-->
      	</div>
	</div>
  <h5 class="field-label required-field">Date of Birth</h5>
  <input type="date" name="dob" id="dob" required><br />
  <h5 class="field-label">Which of the following best describes your situation? (Check all that apply)</h5>
  <?php
  echo implode('<br>', FormUtils::SITUATION_CHECKBOXES);
  ?>
	<h5 class="field-label required-field">Which Role best describes you?</h5>
	<select name="role" class="select-field" required>
    <?php
    echo implode('\n', FormUtils::ROLE_OPTIONS);
    ?>
	</select><br/>
	<h5 class="field-label">How did you hear about SAIL?</h5>
  <input name="reference" type="text" class="text-input-field" /> <br />
  <h5 class="field-label required-field">What is your housing solution timeframe?</h5>
	<select name="timeframe" class="select-field" required>
    <?php
    echo implode('\n', FormUtils::TIMEFRAME_OPTIONS);
    ?>
	 </select><br/>
  <h5 class="field-label">Can we contact you via Email?</h5>
  <?php echo implode('', FormUtils::CONTACT_EMAIL_RADIO) ?>
  <h5 class="field-label">Can we contact you via Text?</h5>
  <?php echo implode('', FormUtils::CONTACT_TEXT_RADIO) ?>
  <h5 class="field-label">Would you like to receive the SAIL Newsletter, get updates from SAIL, and hear about upcoming SAIL events?</h5>
	<?php echo implode('', FormUtils::NEWSLETTER_RADIO) ?>
	<h5 class="field-label">How can SAIL assist you?</h5>
	<textarea name="additionalInfo" class="text-input-field" cols="30" rows="2"></textarea><br /><br />
  <!-- <input type="hidden" name="readTermsOfService" value="0" /> -->
  <input type="checkbox" name="readTermsOfService" value="1" required>
  <label class="field-label required-field" for="readTermsOfService">I have read and agree to the <a target="_blank" href="<? echo Sail\Utils\WebUtils::getUrl() ?>/privacy-policy/">Terms of Service</a>.</label><br/>
  <input type="hidden" name="isPaidMember" value="0" />
</form>
<div class="wp-block-button">
  <? echo Sail\Utils\HtmlUtils::getSailButton('user_reg', 'Submit') ?>
</div>
<script>
  function onChecked(elClass) {
    el=document.getElementsByClassName(elClass);

    var atLeastOneChecked=false;//at least one cb is checked
    for (i=0; i<el.length; i++) {
        if (el[i].checked === true) {
            atLeastOneChecked=true;
        }
    }

    if (atLeastOneChecked === true) {
        for (i=0; i<el.length; i++) {
            el[i].required = false;
        }
    } else {
        for (i=0; i<el.length; i++) {
            el[i].required = true;
        }
    }
  }
</script>