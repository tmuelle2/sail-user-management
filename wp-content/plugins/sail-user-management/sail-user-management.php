<?php
/**
 * Plugin Name: SAIL User Management
 * Plugin URI: https://github.com/tmuelle2/sail-user-management
 * Description: SAIL website user management plugin
 * Version: 0.1
 */

/**
 * Adds the html form required to capture all user account info.
 */
 function user_reg_shortcode($atts = [], $content = null, $tag = '' ) {
 	$o = '<form accept-charset="UTF-8" action="./user-registration.php" id="user_reg" autocomplete="off" method="post" target="_blank">
	<h5>First Name</h5>
  	<input name="firstName" type="text" required /> <br /> 
    <h5>Last Name</h5>
    <input name="lastName" type="text" required /> <br /> 
  	<h5>Email</h5>
    <input name="email" type="email" required /> <br /> 
  	<h5>Password</h5>
    <input name="password" type="password" required /> <br /> 
    <h5>Address Line 1</h5>
    <input name="addrLine1" type="text" required /> <br />
    <h5>Address Line 2</h5>
    <input name="addrLine2" type="text" /> <br />
  	<h5>City</h5>
    <input name="city" type="text" required /> <br />
  	<h5>State</h5>
	<select name="state" required>
      <option value="AL">Alabama</option>
      <option value="AK">Alaska</option>
      <option value="AZ">Arizona</option>
      <option value="AR">Arkansas</option>
      <option value="CA">California</option>
      <option value="CO">Colorado</option>
      <option value="CT">Connecticut</option>
      <option value="DE">Delaware</option>
      <option value="DC">District Of Columbia</option>
      <option value="FL">Florida</option>
      <option value="GA">Georgia</option>
      <option value="HI">Hawaii</option>
      <option value="ID">Idaho</option>
      <option value="IL">Illinois</option>
      <option value="IN">Indiana</option>
      <option value="IA">Iowa</option>
      <option value="KS">Kansas</option>
      <option value="KY">Kentucky</option>
      <option value="LA">Louisiana</option>
      <option value="ME">Maine</option>
      <option value="MD">Maryland</option>
      <option value="MA">Massachusetts</option>
      <option value="MI">Michigan</option>
      <option value="MN">Minnesota</option>
      <option value="MS">Mississippi</option>
      <option value="MO">Missouri</option>
      <option value="MT">Montana</option>
      <option value="NE">Nebraska</option>
      <option value="NV">Nevada</option>
      <option value="NH">New Hampshire</option>
      <option value="NJ">New Jersey</option>
      <option value="NM">New Mexico</option>
      <option value="NY">New York</option>
      <option value="NC">North Carolina</option>
      <option value="ND">North Dakota</option>
      <option value="OH">Ohio</option>
      <option value="OK">Oklahoma</option>
      <option value="OR">Oregon</option>
      <option value="PA">Pennsylvania</option>
      <option value="RI">Rhode Island</option>
      <option value="SC">South Carolina</option>
      <option value="SD">South Dakota</option>
      <option value="TN">Tennessee</option>
      <option value="TX">Texas</option>
      <option value="UT">Utah</option>
      <option value="VT">Vermont</option>
      <option value="VA">Virginia</option>
      <option value="WA">Washington</option>
      <option value="WV">West Virginia</option>
      <option value="WI">Wisconsin</option>
      <option value="WY">Wyoming</option>
	</select> <br />
    <h5>Zip Code</h5>
    <input name="zipCode" type="number" maxlength="5" required /> <br />
   	<h5>Phone Number</h5>
  	<input type="tel" name="phoneNumber" placeholder="3215556789" pattern="[0-9]{3}[0-9]{3}[0-9]{4}" maxlength="10"  title="Ten digit phone number" required/>
  	<h5>Gender</h5>
	<select name="gender" required>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="AZ">Other</option>
      <option value="AZ">Prefer not to say</option>
  	</select>
  	<h5>Date of Birth</h5>
  	<input type="date" name="dob"><br />
  	<h5>Can we contact you via Email?</h5>
	<input checked="checked" name="contactViaEmail" type="radio" value="1" /> Yes<br /> 
	<input name="contactviaemail" type="radio" value="0" /> No <br />  
    <h5>Can we contact you via Text?</h5>
	<input checked="checked" name="contactViaText" type="radio" value="1" /> Yes<br /> 
	<input name="contactviatext" type="radio" value="0" /> No <br /> 
  	<h5>Which Role best describes you?</h5>
  	<select name="role" required>
      <option value="iddchild">A person with an intellectual or developmental disability</option>
      <option value="parent">A parent or legal guardian of someone with an intellectual or developmental disability</option>
  	</select><br/>
    <h5>Which of the following best describes your current living situation?</h5>
  	<select name="situation" required>
      <option value="alone">I live alone in a condo, apartment, or house</option>
      <option value="grouphome">I live in a licensed group home</option>
      <option value="roommates">I live with siblings, relatives, or unrelated roommates that are not my parent(s) or legal guardian(s)</option>
      <option value="parents">I live with my parent(s) or legal guardian(s)</option>
  	</select><br/>
  	<h5>How did you hear about SAIL?</h5>
    <input name="reference" type="text" /> <br /> 
     <h5>What is your housing solution timeframe?</h5>
  	<select name="timeframe" required>
      <option value="asap">As soon as possible</option>
      <option value="1year">Within the next year</option>
      <option value="3years">Within 3 years</option>
      <option value="5years">Within 5 years</option>
      <option value="noplans">No set plans as of now</option>
  	</select><br/>
    <h5>Any Additional Information?</h5>
	<textarea cols="30" rows="2"></textarea><br /> 
  	<h5>Are you interested in joining a SAIL port?</h5>
	<input checked="checked" name="portInterest" type="radio" value="1" /> Yes<br /> 
  	<input name="portInterest" type="radio" value="0" /> No<br /> 
  	<h5>Are you interested in joining a particular SAIL port?</h5>
  	<select name="portInterestParticular" required>
      <option value="Troy">Troy</option>
      <option value="Rochester">Rochester</option>
  	</select><br/>
    <h5>Are you willing to complete a background check?</h5>
	<input checked="checked" name="backgroundCheck" type="radio" value="1" /> Yes<br /> 
  	<input name="backgroundCheck" type="radio" value="0" /> No<br /> 
     <h5>Would you like to receive the SAIL Newsletter?</h5>
	<input checked="checked" name="newsletter" type="radio" value="1" /> Yes<br /> 
  	<input name="newsletter" type="radio" value="0" /> No<br /> 
</form>
<button type="submit" form="user_reg" value="Submit">Submit</button>';
 	return $o;
}

/**
 * Central location to create all shortcodes. Runs on init action.
 */
function shortcodes_init() {
    add_shortcode( 'userregistration', 'user_reg_shortcode' );
} 

add_action( 'init', 'shortcodes_init' );
