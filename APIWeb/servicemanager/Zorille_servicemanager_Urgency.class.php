<?php

/**
 * Gestion de EasyVista Service Manager - Urgency.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Urgency
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Urgency extends item {

    /**
     * Instancie un objet de type Urgency. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Urgency(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Urgency|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Urgency($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Urgency');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * Get urgencies
     * Endpoint: GET /urgency
     * @throws Exception
     */
    public function getUrgency(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/urgency', $parametres);
    }

    /**
     * Get specific urgency
     * Endpoint: GET /urgency/{id}
     * Path params: id
     * @throws Exception
     */
    public function getUrgencyId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/urgency/{id}', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Urgency : 2 operations swagger');
        return $help;
    }
}
