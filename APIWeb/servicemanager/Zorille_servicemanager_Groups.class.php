<?php

/**
 * Gestion de EasyVista Service Manager - Groups.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Groups
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Groups extends item {

    /**
     * Instancie un objet de type Groups. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Groups(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Groups|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Groups($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Groups');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * Get all groups
     * Endpoint: GET /groups
     * @throws Exception
     */
    public function getGroups(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/groups', $parametres);
    }

    /**
     * Create a group
     * Endpoint: POST /groups
     * @throws Exception
     */
    public function postGroups(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/groups', $parametres);
    }

    /**
     * Get specific Group
     * Endpoint: GET /groups/{id}
     * Path params: id
     * @throws Exception
     */
    public function getGroupsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/groups/{id}', $parametres);
    }

    /**
     * Update a group
     * Endpoint: PUT /groups/{id}
     * Path params: id
     * @throws Exception
     */
    public function putGroupsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('put', '/groups/{id}', $parametres);
    }

    /**
     * Update a group
     * Endpoint: PATCH /groups/{id}
     * Path params: id
     * @throws Exception
     */
    public function patchGroupsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('patch', '/groups/{id}', $parametres);
    }

    /**
     * Get specific Group comment
     * Endpoint: GET /groups/{id}/{comment}
     * Path params: id, comment
     * @throws Exception
     */
    public function getGroupsIdCreated(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/groups/{id}/{comment}', $parametres);
    }

    /**
     * Get employees from group
     * Endpoint: GET /groups/{id}/employees
     * Path params: id
     * @throws Exception
     */
    public function getGroupsIdSpecific(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/groups/{id}/employees', $parametres);
    }

    /**
     * Add employee to a group
     * Endpoint: POST /groups/{id}/employee/{EmployeeId}
     * Path params: id, EmployeeId
     * @throws Exception
     */
    public function postGroupsIdEmployeeEmployeeid(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['EmployeeId'])) {
			return $this->onError('Il faut un EmployeeId','',1);
		}
        return $this->execute_operation('post', '/groups/{id}/employee/{EmployeeId}', $parametres);
    }

    /**
     * Remove employee from group
     * Endpoint: DELETE /groups/{id}/employee/{EmployeeId}
     * Path params: id, EmployeeId
     * @throws Exception
     */
    public function deleteGroupsIdEmployeeEmployeeid(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['EmployeeId'])) {
			return $this->onError('Il faut un EmployeeId','',1);
		}
        return $this->execute_operation('delete', '/groups/{id}/employee/{EmployeeId}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Groups : 9 operations swagger');
        return $help;
    }
}
