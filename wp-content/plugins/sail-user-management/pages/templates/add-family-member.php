<details>
    <summary>Link Family Accounts</summary>
    <div>
        <p>By linking your account to your family members' accounts, you can share the benefits of SAIL membership, like Friendship Connect and full access to Housing Roadmap resources.</p>
        <form accept-charset="UTF-8" id="add-family-member" autocomplete="on" action='add-family-member'>
            <input type="hidden" name="action" value="add-family-member">
            <h5 class="field-label required-field">Email of Family Member</h5>
        	<input name="email" type="email" class="text-input-field" required /> <br />
        </form>
        <button type="submit" form="add-family-member" value="Submit">Submit</button>
        <br/>
        <h5 class="field-label">Currently Linked Accounts</h5>
    	<div>
            <?php
            use Sail\Data\Dao\UserDao;
            use Sail\Data\Dao\FamilyDao;
            $sail_user = UserDao::getInstance()->getSailUser();
            $family = FamilyDao::getInstance()->getFamilyMembersForUser($sail_user);
            echo $family->__toString();
            ?>
        </div>
    	<br/>
    </div>
</details>
