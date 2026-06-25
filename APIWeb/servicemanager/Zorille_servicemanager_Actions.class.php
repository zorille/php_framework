<?php

/**
 * Gestion de EasyVista Service Manager - Actions.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Actions
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Actions extends item {

    /**
     * Instancie un objet de type Actions. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Actions(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Actions|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Actions($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Actions');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Actions List
     * Endpoint: GET /actions
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getActions(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/actions', $parametres);
    }

    /**
     * View Action
     * Endpoint: GET /actions/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getActionsId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/actions/{id}', $parametres);
    }

    /**
     * Finish action
     * Endpoint: PUT /actions/{id}
     * Path params: id
     * @throws Exception
     */
    public function putActionsRfcNumber(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('put', '/actions/{id}', $parametres);
    }

    /**
     * Finish action
     * Endpoint: PATCH /actions/{id}
     * Path params: id
     * @throws Exception
     */
    public function patchActionsRfcNumber(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('patch', '/actions/{id}', $parametres);
    }

    /**
     * View Action
     * Endpoint: GET /actions/{id}/{comment}
     * Path params: id, comment
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getActionsCommentId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/actions/{id}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Actions : 5 operations swagger');
        return $help;
    }
}
