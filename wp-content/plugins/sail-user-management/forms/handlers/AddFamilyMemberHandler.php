<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Data\Dao\FamilyDao;
use Sail\Data\Model\Family;
use Sail\Utils\EmailSender;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;
use Sail\Form\Handlers\SailFormHandler;

class AddFamilyMemberHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "add-family-member";
    private UserDao $dao;
    private FamilyDao $fam_dao;

    public function __construct()
    {
        $this->dao = UserDao::getInstance();
        $this->fam_dao = FamilyDao::getInstance();
        parent::__construct(self::ACTION_NAME, true, false);
    }

    public function callback(WP_REST_Request $request)
    {
        $this->log("####################### ADD FAMILY ##########################");
        $emailInput = $request->get_param('email');
        $this_user = $this->dao->getSailUser();
        $link_to_user = $this->dao->getUserByEmail($emailInput, 'ARRAY_A');

        if ($this_user['userId'] == $link_to_user['userId']) {
            $this->log("This user tried to link their account to their own account as a family member: ");
            $this->log(print_r($this_user->userId, true));
            return $this->response400();
        }

        $family_id = null;
        if ($link_to_user['familyId'] != null && isset($link_to_user['familyId']) && $link_to_user['familyId'] > 0) {
            $family_id = $link_to_user['familyId'];
        } else if ($this_user['familyId'] != null && isset($this_user['familyId']) && isset($this_user['familyId']) > 0) {
            $family_id = $this_user['familyId'];
        } else {
            $family_id = $this_user['userId'];
        }

        $relation_array = array('familyId' => $family_id,
                        'userId1' => $this_user['userId'],
                        'userId2' => $link_to_user['userId'],
                        'confirmingUserId' => $link_to_user['userId'],
                        'isConfirmed' => 0);

        $relation = new Family([], $relation_array);

        $this->log("CHECK FOR EXISTING RELATION FIRST");
        $this->log(print_r($this_user['userId'], true));
        $this->log(print_r($link_to_user['userId'], true));
        $this->log($this->fam_dao->doesFamilyRelationExist($this_user['userId'], $link_to_user['userId']));
        if ($this->fam_dao->doesFamilyRelationExist($this_user['userId'], $link_to_user['userId']) == 0) {
            $this->dao->updateUser($this_user, ['familyId' => $family_id]);
            $this->fam_dao->createFamilyRelation($relation);
        }
        else
            return $this->response400();

        // Success redirect
        return $this->response200WithClientsideRedirect('/user');
    }
}
