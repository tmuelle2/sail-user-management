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
 	$o = '<style>
.required-field:after {
	content: " *";
  	color: red;
}
.field-label {
  	margin-bottom: 10px;
}
.text-input-field {
	min-height: 26px;
  	font-size: 16px;
	width: 100%;  
}
.select-field {
  	min-height: 26px;
  	font-size: 16px;
  	margin: 1.425 0 1.425 0;
}
.flex-container {
    display: flex;
}

.flex-child {
    flex: 1;
}  

.flex-child:first-child {
    margin-right: 20px;
} 
</style>
<form accept-charset="UTF-8" action="';
$o .= esc_url(admin_url('admin-post.php'));
$o .= '" id="user_reg" autocomplete="on" method="post" target="_blank">
    <input type="hidden" name="action" value="sail_user_registration">
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
          <h5 class="field-label required-field">Password</h5>
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
    <h5 class="field-label">Profile Picture</h5>
    <input type="file" id="profilePicture" name="profilePicture">	   
  	<h5 class="field-label required-field">Gender</h5>
	<select name="gender" class="select-field" required>
      <option value="Male">Male</option>
      <option value="Female">Female</option>
      <option value="AZ">Other</option>
      <option value="AZ">Prefer not to say</option>
  	</select>
  	<h5 class="field-label">Date of Birth</h5>
  	<input type="date" name="dob"><br />
  	<h5 class="field-label">Can we contact you via Email?</h5>
	<input checked="checked" name="contactViaEmail" type="radio" value="1" /> Yes<br /> 
	<input name="contactViaEmail" type="radio" value="0" /> No <br />  
    <h5 class="field-label">Can we contact you via Text?</h5>
	<input checked="checked" name="contactViaText" type="radio" value="1" /> Yes<br /> 
	<input name="contactViaText" type="radio" value="0" /> No <br /> 
  	<h5 class="field-label required-field">Which Role best describes you?</h5>
  	<select name="role" class="select-field" required>
      <option value="iddchild">A person with an intellectual or developmental disability</option>
      <option value="parent">A parent or legal guardian of someone with an intellectual or developmental disability</option>
  	</select><br/>
    <h5 class="field-label required-field">Which of the following best describes your current living situation?</h5>
  	<select name="situation" class="select-field" required>
      <option value="alone">I live alone in a condo, apartment, or house</option>
      <option value="grouphome">I live in a licensed group home</option>
      <option value="roommates">I live with siblings, relatives, or unrelated roommates that are not my parent(s) or legal guardian(s)</option>
      <option value="parents">I live with my parent(s) or legal guardian(s)</option>
  	</select><br/>
  	<h5 class="field-label">How did you hear about SAIL?</h5>
    <input name="reference" type="text" class="text-input-field" /> <br /> 
     <h5 class="field-label required-field">What is your housing solution timeframe?</h5>
  	<select name="timeframe" class="select-field" required>
      <option value="asap">As soon as possible</option>
      <option value="1year">Within the next year</option>
      <option value="3years">Within 3 years</option>
      <option value="5years">Within 5 years</option>
      <option value="noplans">No set plans as of now</option>
  	</select><br/>
    <h5 class="field-label">Are you interested in joining a SAIL port?</h5>
	<input checked="checked" name="portInterest" type="radio" value="1" /> Yes<br /> 
  	<input name="portInterest" type="radio" value="0" /> No<br /> 
  	<h5 class="field-label">Are you interested in joining a particular SAIL port?</h5>
  	<select name="portInterestParticular" class="select-field">
      <option value="Troy">Troy</option>
      <option value="Rochester">Rochester</option>
  	</select><br/>
    <h5 class="field-label">Are you willing to complete a background check?</h5>
	<input checked="checked" name="backgroundCheck" type="radio" value="1" /> Yes<br /> 
  	<input name="backgroundCheck" type="radio" value="0" /> No<br /> 
    <h5 class="field-label">Would you like to receive the SAIL Newsletter?</h5>
	<input checked="checked" name="newsletter" type="radio" value="1" /> Yes<br /> 
  	<input name="newsletter" type="radio" value="0" /> No<br /> 
  	<h5 class="field-label">Any Additional Information?</h5>
	<textarea name="additionalInfo" class="text-input-field" cols="30" rows="2"></textarea><br /> 
</form>
<button type="submit" form="user_reg" value="Submit">Submit</button>';
 	return $o;
}

/**
 * Adds the html form required to login
 */
 function user_signon_shortcode($atts = [], $content = null, $tag = '' ) {
  $o = '<style>
.required-field:after {
  content: " *";
    color: red;
}
.field-label {
    margin-bottom: 10px;
}
.text-input-field {
  min-height: 26px;
    font-size: 16px;
  width: 100%;  
}
.select-field {
    min-height: 26px;
    font-size: 16px;
    margin: 1.425 0 1.425 0;
}
.flex-container {
    display: flex;
}

.flex-child {
    flex: 1;
}  

.flex-child:first-child {
    margin-right: 20px;
} 
</style>
<form accept-charset="UTF-8" action="';
$o .= esc_url(admin_url('admin-post.php'));
$o .= '" id="user_signon" autocomplete="on" method="post" target="_blank">
    <input type="hidden" name="action" value="sail_user_signon">
    <h5 class="field-label required-field">Email</h5>
    <input name="email" type="email" class="text-input-field" required /> <br /> 
    <h5 class="field-label required-field">Password</h5>
    <input name="password" type="password" class="text-input-field" required /> <br /> 
    <input name="remember" type="checkbox" />
    <label for="remember"> Remember me</label><br>
</form>
<button type="submit" form="user_signon" value="Submit">Login</button>';
  return $o;
}

/**
 * Adds the html to display the current users profile info
 */
function user_profile_shortcode($atts = [], $content = null, $tag = '' ) {
  $user = wp_get_current_user();
  $o = '<div>Welcome ';
  $o .= esc_html($user->data->user_login);
  $o .='!</div>';
  return $o;
} 

/**
 * Central location to create all shortcodes. Runs on init hook.
 */
function sail_plugin_init() {
    add_shortcode( 'userRegistration', 'user_reg_shortcode' );
    add_shortcode( 'userSignOn', 'user_signon_shortcode' );
    add_shortcode( 'userProfile', 'user_profile_shortcode' );
} 

function sail_user_register() {
    $home_dir = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
    include_once($home_dir . 'user-registration.php');
}

function sail_user_signon() {
    $home_dir = '/home2/sailhou1/public_html/wp-content/plugins/sail-user-management/';
    include_once($home_dir . 'user-signon.php');
}

add_action('admin_post_nopriv_sail_user_registration', 'sail_user_register');
add_action('admin_post_sail_user_registration', 'sail_user_register');
add_action('admin_post_nopriv_sail_user_signon', 'sail_user_signon');
add_action('admin_post_sail_user_signon', 'sail_user_signon');
add_action('init', 'sail_plugin_init' );
