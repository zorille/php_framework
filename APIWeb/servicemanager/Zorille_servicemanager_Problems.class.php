<?php

/**
 * Gestion de EasyVista Service Manager - Problems.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Problems
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Problems extends item {

    /**
     * Instancie un objet de type Problems. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Problems(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Problems|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Problems($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Problems');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View problem-links list
     * Endpoint: GET /problem-links
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getProblemLinks(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/problem-links', $parametres);
    }

    /**
     * View Problems list
     * Endpoint: GET /problems
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getProblems(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/problems', $parametres);
    }

    /**
     * View Problem
     * Endpoint: GET /problems/{rfc_number}
     * Path params: rfc_number
     * @throws Exception
     */
    public function getProblemsRfcNumber(array $parametres = array()): static|bool {
		if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('get', '/problems/{rfc_number}', $parametres);
    }

    /**
     * GET /problems/{rfc_number}/requests
     * Endpoint: GET /problems/{rfc_number}/requests
     * Path params: rfc_number
     * @throws Exception
     */
    public function getProblemsRfcNumberCommentRequests(array $parametres = array()): static|bool {
    	if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
        return $this->execute_operation('get', '/problems/{rfc_number}/requests', $parametres);
    }

    /**
     * GET /problems/{rfc_number}/{comment}
     * Endpoint: GET /problems/{rfc_number}/{comment}
     * Path params: rfc_number, comment
     * @throws Exception
     */
    public function getProblemsRfcNumberComment(array $parametres = array()): static|bool {
    	if(!isset($parametres['rfc_number'])) {
			return $this->onError('Il faut un rfc_number','',1);
		}
    	if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/problems/{rfc_number}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Problems : 5 operations swagger');
        return $help;
    }
}
