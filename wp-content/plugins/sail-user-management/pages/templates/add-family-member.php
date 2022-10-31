<details>
    <summary>Link Family Accounts</summary>
    <div>
        <p>By linking your account to your family members' accounts, you can share the benefits of SAIL membership, like Friendship Connect and full access to Housing Roadmap resources.</p>
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
