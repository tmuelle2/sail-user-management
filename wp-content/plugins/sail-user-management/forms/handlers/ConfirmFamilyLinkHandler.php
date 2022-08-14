<?php

namespace Sail\Form\Handlers;

use WP_REST_Request;
use WP_REST_Response;

use Sail\Data\Dao\UserDao;
use Sail\Data\Dao\FamilyDao;
use Sail\Utils\EmailSender;
use Sail\Utils\Logger;
use Sail\Utils\Singleton;
use Sail\Form\Handlers\SailFormHandler;

class ConfirmFamilyLinkHandler extends SailFormHandler
{
    use Logger;
    use Singleton;

    private const ACTION_NAME = "confirm-family-link";
    private UserDao $dao;
    private FamilyDao $daoFam;

    public function __construct()
    {
        $this->dao = UserDao::getInstance();
        $this->daoFam = FamilyDao::getInstance();
        parent::__construct(self::ACTION_NAME, true, false);
    }

    public function callback(WP_REST_Request $request)
    {
        $this->log("####################### CONFIRM FAMILY LINK ##########################");
        $relationId = $request->get_param('relationId');
        $confirmLinkage = $request->get_param('confirmLinkage');

        $relation = $this->daoFam->getFamilyRelationById($relationId);
        $this_user = $this->dao->getSailUser();

        $startedId = $relation->getDatabaseData()["userId1"] != $relation->getDatabaseData()["confirmingUserId"] ? $relation->getDatabaseData()["userId1"] : $relation->getDatabaseData()["userId2"];
        $user_that_started_link = $this->dao->getSailUserById($startedId);

        if ($confirmLinkage) {
            $this->daoFam->updateFamilyRelation($relation, array("isConfirmed" => 1));
            $this->dao->updateUser($this_user, array("familyId" => $user_that_started_link->getDatabaseData()["familyId"]));

            // Success redirect
            return $this->response200WithClientsideRedirect('/user');
        }
        else {
            $this->daoFam->deleteFamilyRelation($relation->getDatabaseData()["relationId"]);

            return $this->response302WithClientsideRedirect('/user');
        }

    }
}
