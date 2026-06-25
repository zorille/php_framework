<?php

/**
 * Gestion de EasyVista Service Manager - Departments.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Departments
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Departments extends item {

    /**
     * Instancie un objet de type Departments. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Departments(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Departments|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Departments($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Departments');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Departments List
     * Endpoint: GET /departments
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getDepartments(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/departments', $parametres);
    }

    /**
     * View Departments
     * Endpoint: GET /departments/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getDepartmentsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/departments/{id}', $parametres);
    }

    /**
     * Update Department
     * Endpoint: PUT /departments/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function putDepartmentsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('put', '/departments/{id}', $parametres);
    }

    /**
     * Update Department
     * Endpoint: PATCH /departments/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function patchDepartmentsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('patch', '/departments/{id}', $parametres);
    }

    /**
     * View Departments
     * Endpoint: GET /departments/{id}/{comment}
     * Path params: id, comment
     * @throws Exception
     */
    public function getDepartmentsCommentId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/departments/{id}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Departments : 5 operations swagger');
        return $help;
    }
}
