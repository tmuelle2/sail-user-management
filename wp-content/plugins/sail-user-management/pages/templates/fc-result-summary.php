<?php
use Sail\Data\Dao\FriendshipConnectDao;
use Sail\Data\Dao\UserDao;

$fcMember = FriendshipConnectDao::getInstance()->getFcProfile();
if (isset($fcMember) && $fcMember->referenceApproved) {
	$activeProfiles = FriendshipConnectDao::getInstance()->getActiveFcProfiles();

	foreach ($activeProfiles as $fcProfile) {
		$sailUser = UserDao::getInstance()->getSailUserById($fcProfile->userId);
		$displayName = $sailUser->firstName;
		if ($fcProfile->namePreference == "First Name and Last Initial") {
			$displayName = "{$sailUser->firstName} {$sailUser->lastName[0]}.";
		} else if ($fcProfile->namePreference == "Initials Only") {
			$displayName = strtoupper("{$sailUser->firstName[0]}.{$sailUser->lastName[0]}.");
		} else if ($fcProfile->namePreference == "Nickname") {
			$displayName = $fcProfile->nickname;
		}
		$contact = "Via Email at " . $fcProfile->primaryContact;
		if ($fcProfile->primaryContactType == "Phone (Text Message)") {
			$contact = "Via Text Message at " . $fcProfile->primaryContact;
		} else if ($fcProfile->primaryContactType == "Phone (Voice Call)") {
			$contact = "Via Voice Call at " . $fcProfile->primaryContact;
		}
		$age = floor((time() - strtotime($sailUser->dob)) / 31556926);
		echo <<< END
		<div style="margin-top: 64px;" class="flex-start">
			<div style="margin: 8px 8px 8px 8px;">
				<img class="pfp" alt="Profile Picture" src="$fcProfile->profilePicture" />
			</div>
			<div style="margin-left: 32px;">
				<div class="flex-container"><h2 id="displayName">$displayName</h2></div>
					<div><strong>Age:&nbsp;</strong><span id="age">$age</span></div>
					<div><strong>Location:&nbsp;</strong><span id="location">{$sailUser->city}, {$sailUser->state}</span></div>
					<div><strong>Activities:&nbsp;</strong><span id="activities">$fcProfile->activities</span></div>
					<div><strong>Hobbies:&nbsp;</strong><span id="hobbies">$fcProfile->hobbies</span></div>
					<details>
						<summary>Show More</summary>
						<div><strong>Typical Day:&nbsp;</strong><span id="typicalDay">$fcProfile->typicalDay</span></div>
						<div><strong>Strengths:&nbsp;</strong><span id="strengths">$fcProfile->strengths</span></div>
						<div><strong>What Makes Me Happy:&nbsp;</strong><span id="makesYouHappy">$fcProfile->makesYouHappy</span></div>
						<div><strong>Life's Vision:&nbsp;</strong><span id="lifesVision">$fcProfile->lifesVision</span></div>
						<div><strong>Support Requirements:&nbsp;</strong><span id="supportRequirements">$fcProfile->supportRequirements</span></div>
						<details>
							<summary>Contact Me</summary>
							<span id="contact"><strong>$contact</strong></span>
						</details>
					</details>
			</div>
		</div>
		END;
	}
}
?>