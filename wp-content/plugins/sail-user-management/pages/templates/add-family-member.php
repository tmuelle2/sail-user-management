<details>
    <summary>Link Family Accounts</summary>
    <div>
        <p>By linking your account to your family members' accounts, you can share the benefits of SAIL membership, like Friendship Connect and full access to Housing Roadmap resources.</p>
        <p>To link Family accounts, please follow the process below:<br><br>

        1. Create SAIL accounts for all family members. Each member needs a unique email address. There will be a primary account member who pays the SAIL dues either at sign up or on their My Profile tab.<br>
        2. To link accounts, the primary account member visits the SAIL website and logs in.<br>
        3. Go to the My Profile page and open the Link Family Account section and type in one email address belonging to a family member's SAIL account that you would like to link.<br>
        4. Click submit and then log out of the website (critical step!).<br>
        5. To finish the linking process to the primary account, the family member needs to confirm the link. The family member will visit the SAIL website and log in.<br>
        6. Go to the My Profile page and open the Link Family Account section, check the circle next to Confirm Family Link and click on the submit button.<br>

        Repeat steps 2 through 6 if multiple family member accounts need to be linked. Once the steps above have been completed, your family accounts are linked. At times, there is a delay in the primary account members' email showing up on the secondary's Link Family Account section. If this happens, please wait a day, and try again.<br>
        Contact info@sailhousingsolutions.org with questions.</p>

        <form accept-charset="UTF-8" id="add_family_member" autocomplete="on" action='add-family-member'>
            <input type="hidden" name="action" value="fam_add">
            <h5 class="field-label required-field">Email of Family Member</h5>
        	<input name="email" type="email" class="text-input-field" required /> <br />
        </form>
        <? echo Sail\Utils\HtmlUtils::getSailButton('add_family_member', 'Submit') ?>
        <br/>
        <div>
            <?php
            use Sail\Data\Dao\UserDao;
            use Sail\Data\Dao\FamilyDao;
            $sail_user = UserDao::getInstance()->getSailUser();
            $family_needs_confirm = FamilyDao::getInstance()->getNeedsConfirmationsForUser($sail_user);
            if (count($family_needs_confirm) > 0) { ?>
                <h5 class="field-label">Needs Confirmation</h5>
                <p>These accounts have started a family link to your account, please review and submit.</p>
            <?php
                foreach ($family_needs_confirm as $relation) {
                    echo $relation->__toConfirmationString();
                }
            }
            ?>
        </div>
        <div>
            <?php

            //$sail_user = UserDao::getInstance()->getSailUser();
            $family_pending = FamilyDao::getInstance()->getPendingFamilyMembersForUser($sail_user);

            if ($family_pending->__toString() != "None") { ?>
                <h5 class="field-label">Pending Linked Accounts</h5>
            <?php
                echo $family_pending->__toString();
            }
            ?>
        </div>
    	<div>
            <?php
            //$sail_user = UserDao::getInstance()->getSailUser();
            $family = FamilyDao::getInstance()->getFamilyMembersForUser($sail_user);
            if ($family->__toString() != "None") { ?>
                <h5 class="field-label">Currently Linked Accounts</h5>
            <?php
                echo $family->__toString();
            }
            ?>
        </div>
    	<br/>
    </div>
</details>
