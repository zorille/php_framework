<?php

/**
 * Gestion de EasyVista Service Manager - Slas.
 * Genere depuis le swagger 1.9.3.
 */
namespace Zorille\servicemanager;

use Exception;
use Zorille\framework as Core;

/**
 * class Slas
 *
 * @package Lib
 * @subpackage servicemanager
 */
class Slas extends item {

    /**
     * Instancie un objet de type Slas. @codeCoverageIgnore
     * @throws Exception
     */
    static function &creer_Slas(
        Core\options &$liste_option,
        &$webservice_rest,
        bool|string $sort_en_erreur = false,
        string $entete = __CLASS__,
        string|int $account = '40000'): Slas|static {
        Core\abstract_log::onDebug_standard(__METHOD__, 1);
        $objet = new Slas($sort_en_erreur, $entete);
        $objet->_initialise(array(
            'options' => $liste_option,
            'wsclient' => $webservice_rest,
            'account' => $account
        ));
        return $objet;
    }

    public function &_initialise(array $liste_class): static {
        parent::_initialise($liste_class);
        return $this->setFormat('Slas');
    }

    public function __construct(bool|string $sort_en_erreur = false, string $entete = __CLASS__) {
        parent::__construct($sort_en_erreur, $entete);
    }

    /**
     * View Slas List
     * Endpoint: GET /slas
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getSlas(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/slas', $parametres);
    }

    /**
     * View Sla
     * Endpoint: GET /slas/{id}
     * Path params: id
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getSlasId(array $parametres = array()): static|bool {
        if(!isset($parametres['id'])) {
			return $this->onError('Il faut un id','',1);
		}
        return $this->execute_operation('get', '/slas/{id}', $parametres);
    }

    /**
     * View Status
     * Endpoint: GET /status
     * Query params: max_rows, sort, fields, search
     * @throws Exception
     */
    public function getAllStatus(array $parametres = array()): static|bool {
        return $this->execute_operation('get', '/status', $parametres);
    }

    static public function help(): array|string {
        $help = parent::help();
        $help[__CLASS__]['text'] = array('Slas : 3 operations swagger');
        return $help;
    }
}
