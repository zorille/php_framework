<?php

/**
 * Gestion de EasyVista Service Manager - Known Errors.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class KnownErrors
 *
 * @package Lib
 * @subpackage servicemanager
 */
class KnownErrors extends item {

    /**
     * Instancie un objet de type KnownErrors. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_KnownErrors(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): KnownErrors|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new KnownErrors($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('KnownErrors');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * Known Errors List
     * Endpoint: GET /known-errors
     * @throws Exception
     */
    public function getKnownErrors(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/known-errors', $parametres);
    }

    /**
     * Known Errors List
     * Endpoint: GET /known-errors/{id}
     * Path params: id
     * @throws Exception
     */
    public function getSpecificKnownErrors(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/known-errors/{id}', $parametres);
    }

    /**
     * Known Errors List
     * Endpoint: GET /known-errors/{id}/{comment}
     * Path params: id, comment
     * @throws Exception
     */
    public function getKnownErrorsComment(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        if(!isset($parametres['comment'])) {
			return $this->onError('Il faut un comment','',1);
		}
        return $this->execute_operation('get', '/known-errors/{id}/{comment}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('KnownErrors : 3 operations swagger');
        return $help;
    }
}
