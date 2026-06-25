<?php

/**
 * Gestion de EasyVista Service Manager - Domain.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Domain
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Domain extends item {

    /**
     * Instancie un objet de type Domain. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Domain(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Domain|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Domain($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Domain');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * Retrieves a list of available domains.
     * Endpoint: GET /domains
     * @throws Exception
     */
    public function getDomain(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/domains', $parametres);
    }

    /**
     * Retrieves the details of a specific domain.
     * Endpoint: GET /domains/{id}
     * Path params: id
     * @throws Exception
     */
    public function getDomainId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/domains/{id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Domain : 2 operations swagger');
        return $help;
    }
}
