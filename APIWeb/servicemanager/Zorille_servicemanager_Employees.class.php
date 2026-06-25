<?php

/**
 * Gestion de EasyVista Service Manager - Employees.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Employees
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Employees extends item {

    /**
     * Instancie un objet de type Employees. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Employees(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Employees|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Employees($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Employees');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Employees List
     * Endpoint: GET /employees
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getEmployees(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/employees', $parametres);
    }

    /**
     * Create Employee
     * Endpoint: POST /employees
     * @throws Exception
     */
    public function postEmployees(array $parametres = array()): static|bool {
        return $this->execute_operation('post', '/employees', $parametres);
    }

    /**
     * View Employees List
     * Endpoint: GET /employees/{id}/{comment}
     * Path params: comment, id
     * @throws Exception
     */
    public function getCommentEmployees(array $parametres = array()): static|bool {
    	if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
    	if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/employees/{id}/{comment}', $parametres);
    }

    /**
     * View Employee
     * Endpoint: GET /employees/{id}
     * Path params: id
     * @throws Exception
     */
    public function getEmployeesEmployeeId(array $parametres = array()): static|bool {
    	if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/employees/{id}', $parametres);
    }

    /**
     * Update Employee
     * Endpoint: PUT /employees/{id}
     * Path params: id
     * @throws Exception
     */
    public function putEmployeesId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('put', '/employees/{id}', $parametres);
    }

    /**
     * Update Employee
     * Endpoint: PATCH /employees/{id}
     * Path params: id
     * @throws Exception
     */
    public function patchEmployeesId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('patch', '/employees/{id}', $parametres);
    }

    /**
     * View Employee from group
     * Endpoint: GET /employees/{id}/groups
     * Path params: id
     * @throws Exception
     */
    public function getEmployeesFromGroupEmployeeId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/employees/{id}/groups', $parametres);
    }

    /**
     * View employee's domains
     * Endpoint: GET /employees/{id}/domains
     * Path params: id
     * @throws Exception
     */
    public function getEmployeesDomainsfromemployeeEmployeeId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/employees/{id}/domains', $parametres);
    }

    /**
     * Affect a domain to the employee
     * Endpoint: POST /employees/{id}/domains
     * Path params: id
     * @throws Exception
     */
    public function postEmployeesIdDomains(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('post', '/employees/{id}/domains', $parametres);
    }

    /**
     * Remove Domain from employee
     * Endpoint: DELETE /employees/{parent_id}/domains/{child_ID}
     * Path params: parent_id, child_ID
     * @throws Exception
     */
    public function deleteEmployeesParentIdDomainsChildId(array $parametres = array()): static|bool {
        if(!isset($parametres['parent_id'])) {
			return $this->onError('Il faut un parent_id','',1);
		}
        if(!isset($parametres['child_ID'])) {
			return $this->onError('Il faut un child_ID','',1);
		}
        return $this->execute_operation('delete', '/employees/{parent_id}/domains/{child_ID}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Employees : 10 operations swagger');
        return $help;
    }
}
